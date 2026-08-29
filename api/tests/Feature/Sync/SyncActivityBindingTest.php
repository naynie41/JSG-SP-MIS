<?php

declare(strict_types=1);

namespace Tests\Feature\Sync;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Sync\Enums\ConflictPolicy;
use App\Domain\Sync\Enums\SyncTrigger;
use App\Domain\Sync\Models\SyncConnector;
use App\Domain\Sync\Services\SyncEngine;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ConfirmsImportMapping;
use Tests\TestCase;

/**
 * Activity binding on the sync door (activity-first).
 *
 * An upload cannot bring people into the registry without naming the activity that
 * brought them — that is what makes a delivery attributable to an MDA-run instance
 * rather than floating in the register. Sync had no such binding: a connector ingested
 * people attached to nothing, so the same records arriving through a file and through a
 * connector produced different registries.
 *
 * A connector therefore declares a target activity in its configuration, and binds every
 * synced row to it exactly as an upload binds to the selected one. There is no
 * activity-less sync ingestion: a connector without one is HELD, using the same
 * mechanism that holds a connector whose column mapping is unconfirmed.
 */
class SyncActivityBindingTest extends TestCase
{
    use ConfirmsImportMapping, RefreshDatabase;

    private Mda $mda;

    private Programme $programme;

    private Activity $activity;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->users['sysAdmin'] = $this->user(null, RoleKey::SystemAdministrator);
        $this->users['officer'] = $this->user($this->mda, RoleKey::MdaAdmin);

        $this->programme = Programme::factory()->create();
        $this->activity = Activity::factory()->forProgramme($this->programme, $this->mda)->create([
            'status' => 'active',
            'created_by' => $this->users['officer']->id,
        ]);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function connector(?string $activityId = null): SyncConnector
    {
        $connector = SyncConnector::factory()->create([
            'owner_mda_id' => $this->mda->id,
            'source' => RegistrationSource::Socu,
            'conflict_policy' => ConflictPolicy::FlagForReview,
            'activity_id' => $activityId,
        ]);

        return $this->confirmConnectorMapping($connector, $this->users['sysAdmin']);
    }

    /** @param array<int, array<string, mixed>> $records */
    private function mockSocu(array $records): void
    {
        config(['sync.mock_records.socu' => $records]);
    }

    private function engine(): SyncEngine
    {
        return app(SyncEngine::class);
    }

    /* ------------------------------------------------------------- the binding */

    public function test_a_synced_record_is_enrolled_against_the_connectors_activity(): void
    {
        $this->mockSocu([
            ['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'phone' => '08030000001', 'id' => 'SOCU-1'],
        ]);

        $run = $this->engine()->runConnector($this->connector($this->activity->id), SyncTrigger::Scheduled);

        $this->assertSame('completed', $run->status->value);
        $this->assertSame(1, $run->created_count);

        $beneficiary = Beneficiary::query()->firstOrFail();
        $enrollment = Enrollment::query()->where('beneficiary_id', $beneficiary->id)->first();

        $this->assertNotNull($enrollment, 'A synced beneficiary must be bound to the connector activity.');
        $this->assertSame($this->activity->id, $enrollment->activity_id);
        $this->assertSame($this->programme->id, $enrollment->programme_id);
        // The enrolling MDA is the connector's, not whoever happened to trigger the run.
        $this->assertSame($this->mda->id, $enrollment->mda_id);
    }

    /* --------------------------------------------------- no activity-less ingestion */

    public function test_a_connector_without_an_activity_is_held_and_ingests_nothing(): void
    {
        $this->mockSocu([
            ['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'phone' => '08030000001', 'id' => 'SOCU-1'],
        ]);

        $run = $this->engine()->runConnector($this->connector(null), SyncTrigger::Scheduled);

        $this->assertSame('failed', $run->status->value);
        $this->assertStringContainsString('activity', mb_strtolower((string) $run->error));
        // Held, not partially applied: nothing may reach the registry unattached.
        $this->assertSame(0, Beneficiary::query()->count());
    }

    public function test_the_hold_is_audited_so_an_admin_can_see_why_it_stopped(): void
    {
        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'SOCU-1']]);

        $this->engine()->runConnector($this->connector(null), SyncTrigger::Scheduled);

        $this->assertDatabaseHas('audit_log', ['action' => 'sync.run_blocked']);
    }

    /* ------------------------------------------- self-owned re-sync (FR-DUP-10) */

    public function test_re_syncing_a_record_this_mda_already_owns_creates_no_duplicate(): void
    {
        // The sync analogue of a self-owned re-upload: the person is already ours, so
        // there is nothing to match and no one to ask. It must not become a second
        // person, and it must not raise a request to serve a record we already hold.
        $connector = $this->connector($this->activity->id);
        $this->mockSocu([
            ['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'phone' => '08030000001', 'id' => 'SOCU-1'],
        ]);

        $this->engine()->runConnector($connector, SyncTrigger::Scheduled);
        $this->assertSame(1, Beneficiary::query()->count());

        // The same person again, under a DIFFERENT source id so idempotency cannot be
        // what saves us — this has to resolve as a self-owned match.
        $this->mockSocu([
            ['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'phone' => '08030000001', 'id' => 'SOCU-2'],
        ]);
        $second = $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        $this->assertSame(1, Beneficiary::query()->count(), 'A self-owned re-sync must never create a second record.');
        $this->assertSame(0, $second->created_count);
        $this->assertDatabaseCount('service_requests', 0);
    }

    public function test_an_idempotent_re_sync_does_not_enroll_twice(): void
    {
        $connector = $this->connector($this->activity->id);
        $this->mockSocu([
            ['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'phone' => '08030000001', 'id' => 'SOCU-1'],
        ]);

        $this->engine()->runConnector($connector, SyncTrigger::Scheduled);
        $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        $this->assertSame(1, Enrollment::query()->count(), 'Re-running a connector must not enroll the same person again.');
    }
}
