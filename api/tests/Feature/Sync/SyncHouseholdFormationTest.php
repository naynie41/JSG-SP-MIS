<?php

declare(strict_types=1);

namespace Tests\Feature\Sync;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Enums\HouseholdRole;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use App\Domain\Registry\Services\ImportCommitter;
use App\Domain\Sync\Enums\SyncTrigger;
use App\Domain\Sync\Models\SyncConnector;
use App\Domain\Sync\Services\SyncEngine;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\ConfirmsImportMapping;
use Tests\TestCase;

/**
 * Households must be formed from the source household-reference on EVERY source, not
 * just the file-upload ones (PRD FR-REG-01/02, §9).
 *
 * The file pipeline does this in {@see ImportCommitter}
 * and REST intake does it in the intake controller. Sync — which is how SOCU, other
 * government systems, and flushed offline batches arrive — creates beneficiaries
 * through the same registrar, so it has to form households the same way. Since manual
 * creation was removed, a source that carries household grouping has NO other way to
 * express it: whatever sync drops here is simply lost.
 */
class SyncHouseholdFormationTest extends TestCase
{
    use ConfirmsImportMapping, RefreshDatabase;

    private Mda $mda;

    private User $officer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->officer = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
    }

    private function connector(): SyncConnector
    {
        return $this->confirmConnectorMapping(SyncConnector::factory()->create([
            'owner_mda_id' => $this->mda->id,
            'source' => RegistrationSource::Socu,
        ]), $this->officer);
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

    private function households(): int
    {
        return Household::query()->withoutGlobalScopes()->count();
    }

    /* ---------------------------------------------------- forming from the source */

    public function test_a_connector_forms_the_household_from_the_source_reference(): void
    {
        $connector = $this->connector();
        $this->mockSocu([
            ['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'SOCU-1', 'household_id' => 'HH-100', 'household_head' => 'yes'],
            ['first_name' => 'Chidi', 'last_name' => 'Okoye', 'nin' => '20000000002', 'id' => 'SOCU-2', 'household_id' => 'HH-100', 'household_role' => 'spouse'],
        ]);

        $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        $this->assertSame(1, $this->households(), 'the two records share one household reference');

        $household = Household::query()->withoutGlobalScopes()->firstOrFail();
        $this->assertSame($this->mda->id, $household->owner_mda_id, 'the household belongs to the importing MDA');
        $this->assertSame('HH-100', $household->original_record_id);
        $this->assertSame(RegistrationSource::Socu, $household->registration_source);

        // Both members are attached, with the head designated from the source flag.
        $memberships = HouseholdMembership::query()->withoutGlobalScopes()
            ->where('household_id', $household->id)->get();
        $this->assertCount(2, $memberships);

        // Looked up by name, not NIN — NIN is encrypted at rest, so exact match runs on
        // the keyed nin_hash column rather than the raw value (SECURITY.md §4).
        $head = Beneficiary::query()->withoutGlobalScopes()->where('first_name', 'Ada')->firstOrFail();
        $this->assertSame($head->id, $household->head_beneficiary_id);
        $this->assertSame(
            HouseholdRole::Spouse,
            $memberships->firstWhere('beneficiary_id', '!=', $head->id)->role_in_household,
        );
    }

    public function test_two_different_references_form_two_households(): void
    {
        $connector = $this->connector();
        $this->mockSocu([
            ['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'SOCU-1', 'household_id' => 'HH-100'],
            ['first_name' => 'Bala', 'last_name' => 'Sani', 'nin' => '20000000002', 'id' => 'SOCU-2', 'household_id' => 'HH-200'],
        ]);

        $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        $this->assertSame(2, $this->households());
    }

    public function test_a_record_without_a_household_reference_forms_none(): void
    {
        $connector = $this->connector();
        $this->mockSocu([
            ['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'SOCU-1'],
        ]);

        $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        // A beneficiary-only source is legitimate — it must not invent a household.
        $this->assertSame(1, Beneficiary::query()->withoutGlobalScopes()->count());
        $this->assertSame(0, $this->households());
    }

    /* ------------------------------------------------------------- idempotency */

    public function test_re_running_the_same_connector_does_not_duplicate_the_household(): void
    {
        $connector = $this->connector();
        $this->mockSocu([
            ['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'SOCU-1', 'household_id' => 'HH-100'],
        ]);

        $this->engine()->runConnector($connector, SyncTrigger::Scheduled);
        $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        $this->assertSame(1, $this->households(), 're-sync must find the household, not create a second');
        $this->assertSame(
            1,
            HouseholdMembership::query()->withoutGlobalScopes()->count(),
            're-sync must not open a second membership',
        );
    }

    public function test_the_single_open_membership_rule_holds_across_sources(): void
    {
        // The beneficiary already belongs to a household formed by an earlier import.
        $existing = Household::factory()->create(['owner_mda_id' => $this->mda->id]);
        $beneficiary = Beneficiary::factory()->create([
            'owner_mda_id' => $this->mda->id,
            'nin' => '20000000001',
        ]);
        HouseholdMembership::create([
            'household_id' => $existing->id,
            'beneficiary_id' => $beneficiary->id,
            'role_in_household' => HouseholdRole::Head,
            'joined_at' => now(),
        ]);

        // A sync record naming a DIFFERENT household must not silently move them.
        $connector = $this->connector();
        $this->mockSocu([
            ['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', 'id' => 'SOCU-1', 'household_id' => 'HH-999'],
        ]);

        $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        $open = HouseholdMembership::query()->withoutGlobalScopes()
            ->where('beneficiary_id', $beneficiary->id)
            ->whereNull('left_at')
            ->get();

        $this->assertCount(1, $open, 'a beneficiary may hold only one open membership');
        $this->assertSame($existing->id, $open->first()->household_id, 'sync must never move a beneficiary between households');
    }

    /* ------------------------------------------------------- the offline-batch door */

    public function test_an_offline_batch_forms_households_too(): void
    {
        $response = $this->withToken($this->officer->createToken('t')->plainTextToken)
            ->json('POST', '/api/v1/sync/offline-batches', [
                'source' => 'kobo',
                'records' => [
                    ['first_name' => 'Ada', 'last_name' => 'Okoye', 'nin' => '20000000001', '_id' => 'OFF-1', 'household_id' => 'HH-500', 'household_head' => 'yes'],
                    ['first_name' => 'Chidi', 'last_name' => 'Okoye', 'nin' => '20000000002', '_id' => 'OFF-2', 'household_id' => 'HH-500'],
                ],
            ]);

        $response->assertSuccessful();

        $this->assertSame(1, $this->households(), 'an offline batch carries household grouping like any other source');
        $this->assertSame(
            2,
            HouseholdMembership::query()->withoutGlobalScopes()->count(),
        );
    }
}
