<?php

declare(strict_types=1);

namespace Tests\Feature\Sync;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Sync\Enums\ConflictPolicy;
use App\Domain\Sync\Enums\SyncTrigger;
use App\Domain\Sync\Models\SyncConnector;
use App\Domain\Sync\Services\SyncEngine;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Scheduled/triggered + offline-batch synchronization (FR-DSH-02, FR-REG-08). Inbound
 * records run the SAME import pipeline (validation → dedup → ownership/provenance →
 * idempotency); sync adds automated, configurable conflict handling; every record is
 * logged; nothing double-inserts.
 */
class DataSyncTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class); // so the dedup screener has an active config

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['sysAdmin'] = $this->user(null, RoleKey::SystemAdministrator);
        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda?->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function connector(ConflictPolicy $policy = ConflictPolicy::FlagForReview): SyncConnector
    {
        return SyncConnector::factory()->create([
            'owner_mda_id' => $this->mdaA->id,
            'source' => RegistrationSource::Socu,
            'conflict_policy' => $policy,
        ]);
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

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /* ------------------------------------------------ ingestion through the pipeline */

    public function test_scheduled_sync_ingests_new_records_with_ownership_and_provenance(): void
    {
        $connector = $this->connector();
        $this->mockSocu([
            ['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'phone' => '08030000001', 'id' => 'SOCU-1'],
        ]);

        $run = $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        $this->assertSame('completed', $run->status->value);
        $this->assertSame(1, $run->fetched_count);
        $this->assertSame(1, $run->created_count);

        $beneficiary = Beneficiary::query()->where('original_record_id', 'SOCU-1')->firstOrFail();
        $this->assertSame($this->mdaA->id, $beneficiary->owner_mda_id);        // ownership = connector MDA
        $this->assertSame(RegistrationSource::Socu, $beneficiary->registration_source); // provenance
        $this->assertSame('SOCU-1', $beneficiary->idempotency_key);
        $this->assertSame('Ada', $beneficiary->first_name);

        // Logged.
        $this->assertDatabaseHas('sync_run_rows', ['sync_run_id' => $run->id, 'original_record_id' => 'SOCU-1', 'outcome' => 'created']);
        $this->assertDatabaseHas('audit_log', ['action' => 'sync.run']);
    }

    public function test_malformed_identity_field_is_rejected_and_not_saved(): void
    {
        $connector = $this->connector();
        $this->mockSocu([
            ['first_name' => 'Bad', 'last_name' => 'Record', 'nin' => '123', 'id' => 'SOCU-BAD'], // NIN present but malformed
        ]);

        $run = $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        $this->assertSame(1, $run->rejected_count);
        $this->assertSame(0, $run->created_count);
        $this->assertDatabaseMissing('beneficiaries', ['original_record_id' => 'SOCU-BAD']);
        $this->assertDatabaseHas('sync_run_rows', ['original_record_id' => 'SOCU-BAD', 'outcome' => 'rejected_identity']);
    }

    /* ---------------------------------------------------------------- idempotency */

    public function test_re_running_a_sync_does_not_double_insert(): void
    {
        $connector = $this->connector();
        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000002', 'id' => 'SOCU-2']]);

        $this->engine()->runConnector($connector, SyncTrigger::Scheduled);
        $second = $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        $this->assertSame(1, Beneficiary::query()->where('idempotency_key', 'SOCU-2')->count());
        $this->assertSame(0, $second->created_count);
        $this->assertSame(1, $second->skipped_count); // skipped_idempotent under flag-for-review
    }

    /* --------------------------------------------------------- conflict handling */

    public function test_exact_duplicate_owned_by_another_mda_is_flagged_never_merged(): void
    {
        $existing = Beneficiary::factory()->withoutBvn()->create(['owner_mda_id' => $this->mdaB->id, 'nin' => '20000000009', 'first_name' => 'Owned']);

        $connector = $this->connector(ConflictPolicy::FlagForReview);
        $this->mockSocu([['first_name' => 'Incoming', 'last_name' => 'Dupe', 'nin' => '20000000009', 'id' => 'SOCU-DUP']]);

        $run = $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        $this->assertSame(1, $run->flagged_count);
        $this->assertSame(0, $run->created_count);
        $this->assertSame('Owned', $existing->fresh()->first_name);               // untouched
        $this->assertSame($this->mdaB->id, $existing->fresh()->owner_mda_id);      // ownership unchanged
        $this->assertDatabaseHas('sync_run_rows', ['sync_run_id' => $run->id, 'outcome' => 'flagged_conflict', 'match_band' => 'exact']);
    }

    public function test_last_write_wins_updates_the_source_owned_record(): void
    {
        $connector = $this->connector(ConflictPolicy::LastWriteWins);

        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000005', 'id' => 'S5']]);
        $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        // Same source record (S5) re-synced with a changed name → the source wins.
        $this->mockSocu([['first_name' => 'Amina', 'last_name' => 'Okoye', 'nin' => '20000000005', 'id' => 'S5']]);
        $run = $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        $this->assertSame(1, $run->updated_count);
        $this->assertSame(1, Beneficiary::query()->where('idempotency_key', 'S5')->count());
        $this->assertSame('Amina', Beneficiary::query()->where('idempotency_key', 'S5')->firstOrFail()->first_name);
    }

    /* ------------------------------------------------------- offline batch (HTTP) */

    public function test_offline_batch_syncs_without_duplicating(): void
    {
        $body = [
            'source' => 'csv',
            'records' => [
                ['first_name' => 'Musa', 'last_name' => 'Bello', 'nin' => '20000000007', 'phone' => '08030000007', 'id' => 'OFF-1'],
            ],
        ];

        $first = $this->send('officerA', 'POST', '/api/v1/sync/offline-batches', $body)->assertStatus(201);
        $this->assertSame(1, $first->json('data.summary.created'));

        // Re-flush the same batch → idempotent, no duplicate.
        $second = $this->send('officerA', 'POST', '/api/v1/sync/offline-batches', $body)->assertStatus(201);
        $this->assertSame(0, $second->json('data.summary.created'));
        $this->assertSame(1, $second->json('data.summary.skipped'));

        $this->assertSame(1, Beneficiary::query()->where('idempotency_key', 'OFF-1')->count());
        $beneficiary = Beneficiary::query()->where('idempotency_key', 'OFF-1')->firstOrFail();
        $this->assertSame($this->mdaA->id, $beneficiary->owner_mda_id); // owned by the capturing MDA
    }

    /* -------------------------------------------------- endpoints + permissions */

    public function test_sync_admin_endpoints_are_permission_gated(): void
    {
        $connector = $this->connector();

        // System Administrator can view connectors + runs and trigger a sync.
        $this->send('sysAdmin', 'GET', '/api/v1/sync/connectors')->assertOk()->assertJsonCount(1, 'data.connectors');
        $this->send('sysAdmin', 'GET', '/api/v1/sync/runs')->assertOk();
        $this->send('sysAdmin', 'POST', "/api/v1/sync/connectors/{$connector->id}/run")->assertStatus(202);

        // An MDA officer has neither sync.view nor sync.run.
        $this->send('officerA', 'GET', '/api/v1/sync/connectors')->assertStatus(403);
        $this->send('officerA', 'POST', "/api/v1/sync/connectors/{$connector->id}/run")->assertStatus(403);
    }
}
