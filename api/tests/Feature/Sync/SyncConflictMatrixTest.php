<?php

declare(strict_types=1);

namespace Tests\Feature\Sync;

use App\Domain\Access\Models\Mda;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Sync\Enums\ConflictPolicy;
use App\Domain\Sync\Enums\SyncTrigger;
use App\Domain\Sync\Jobs\RunDueSyncConnectors;
use App\Domain\Sync\Jobs\RunSyncConnector;
use App\Domain\Sync\Models\SyncConnector;
use App\Domain\Sync\Models\SyncRun;
use App\Domain\Sync\Services\SyncEngine;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\Concerns\ConfirmsImportMapping;
use Tests\TestCase;

/**
 * The conflict × idempotency matrix (FR-DSH-02), and the scheduling that drives it.
 *
 * {@see DataSyncTest} covers one cell of each rule; this covers the cells that decide
 * whether the policy is real:
 *
 *  - a PROBABLE match is flagged under EVERY policy — last-write-wins must never be a
 *    licence to auto-merge an uncertain match (the FR-DUP-09 principle, applied where
 *    there is no officer to adjudicate);
 *  - flag-for-review really does refuse to write, including on a record the source's
 *    own MDA owns;
 *  - a re-sync under last-write-wins UPDATES rather than skips, and under
 *    flag-for-review SKIPS rather than updates — the two policies must diverge on the
 *    same input, or the setting is decorative;
 *  - the scheduler only fans out to ENABLED connectors.
 */
class SyncConflictMatrixTest extends TestCase
{
    use ConfirmsImportMapping, RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);
    }

    private function connector(ConflictPolicy $policy, bool $enabled = true): SyncConnector
    {
        return $this->confirmConnectorMapping(SyncConnector::factory()->bound()->create([
            'owner_mda_id' => $this->mdaA->id,
            'source' => RegistrationSource::Socu,
            'conflict_policy' => $policy,
            'enabled' => $enabled,
        ]));
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

    /* ------------------------------------ a probable match is never auto-merged */

    public function test_a_probable_match_is_flagged_even_under_last_write_wins(): void
    {
        // Same person by name + DOB + locality, no identifier — the fuzzy band. There is
        // no officer in a sync run, so an uncertain match must never be resolved
        // automatically, whatever the policy says about records the source owns.
        $existing = Beneficiary::factory()->withoutBvn()->create([
            'owner_mda_id' => $this->mdaA->id,
            'first_name' => 'Halima', 'last_name' => 'Yusuf',
            'nin' => null, 'phone' => '08031111111',
            'date_of_birth' => '1990-04-12', 'lga' => 'dutse',
        ]);

        $connector = $this->connector(ConflictPolicy::LastWriteWins);
        $this->mockSocu([[
            'first_name' => 'Halima', 'last_name' => 'Yusuf',
            'phone' => '08031111111', 'date_of_birth' => '1990-04-12', 'lga' => 'dutse',
            'id' => 'SOCU-PROB',
        ]]);

        $run = $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        $this->assertSame(0, $run->updated_count, 'a probable match must never be auto-updated');
        $this->assertSame(0, $run->created_count, 'nor silently created as a second record');
        $this->assertSame(1, $run->flagged_count);
        $this->assertSame('Halima', $existing->fresh()->first_name);

        $this->assertDatabaseHas('sync_run_rows', [
            'sync_run_id' => $run->id,
            'outcome' => 'flagged_conflict',
            'match_band' => 'probable',
        ]);
    }

    /* --------------------------------- flag-for-review refuses to write at all */

    public function test_flag_for_review_does_not_update_even_a_record_the_source_owns(): void
    {
        $existing = Beneficiary::factory()->withoutBvn()->create([
            'owner_mda_id' => $this->mdaA->id,
            'nin' => '20000000011',
            'first_name' => 'Original',
        ]);

        $connector = $this->connector(ConflictPolicy::FlagForReview);
        $this->mockSocu([['first_name' => 'Changed', 'last_name' => 'Name', 'nin' => '20000000011', 'id' => 'SOCU-FFR']]);

        $run = $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        // The policy's whole point: surface it, change nothing.
        $this->assertSame(1, $run->flagged_count);
        $this->assertSame(0, $run->updated_count);
        $this->assertSame('Original', $existing->fresh()->first_name);
    }

    /* ------------------------- the two policies diverge on identical input */

    public function test_a_resync_updates_under_last_write_wins_and_skips_under_flag_for_review(): void
    {
        $record = fn (string $name): array => [
            'first_name' => $name, 'last_name' => 'Danjuma', 'nin' => '20000000012', 'id' => 'SOCU-SAME',
        ];

        // Seed the record once through a last-write-wins connector.
        $lww = $this->connector(ConflictPolicy::LastWriteWins);
        $this->mockSocu([$record('First')]);
        $this->engine()->runConnector($lww, SyncTrigger::Scheduled);

        // Re-sync the SAME source id with a changed name → the source wins.
        $this->mockSocu([$record('Second')]);
        $updated = $this->engine()->runConnector($lww, SyncTrigger::Scheduled);

        $this->assertSame(1, $updated->updated_count);
        $this->assertSame(0, $updated->skipped_count);
        $this->assertSame('Second', Beneficiary::query()->where('idempotency_key', 'SOCU-SAME')->firstOrFail()->first_name);

        // The same input under flag-for-review leaves it alone.
        $ffr = $this->connector(ConflictPolicy::FlagForReview);
        $this->mockSocu([$record('Third')]);
        $skipped = $this->engine()->runConnector($ffr, SyncTrigger::Scheduled);

        $this->assertSame(1, $skipped->skipped_count);
        $this->assertSame(0, $skipped->updated_count);
        $this->assertSame('Second', Beneficiary::query()->where('idempotency_key', 'SOCU-SAME')->firstOrFail()->first_name);

        // …and either way there is exactly one record for that source id.
        $this->assertSame(1, Beneficiary::query()->where('idempotency_key', 'SOCU-SAME')->count());
    }

    public function test_an_omitted_source_field_never_wipes_an_existing_value(): void
    {
        $connector = $this->connector(ConflictPolicy::LastWriteWins);
        $this->mockSocu([[
            'first_name' => 'Zainab', 'last_name' => 'Garba', 'nin' => '20000000013',
            'phone' => '08032222222', 'id' => 'SOCU-PARTIAL',
        ]]);
        $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        // A later payload that simply doesn't carry the phone must not blank it —
        // "last write wins" is about conflicting values, not about absence.
        $this->mockSocu([['first_name' => 'Zainab', 'last_name' => 'Garba', 'nin' => '20000000013', 'id' => 'SOCU-PARTIAL']]);
        $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        $this->assertSame('08032222222', Beneficiary::query()->where('idempotency_key', 'SOCU-PARTIAL')->firstOrFail()->phone);
    }

    /* --------------------------------------------- idempotency is per owner MDA */

    public function test_the_same_source_id_under_a_different_mda_is_a_different_record(): void
    {
        $a = $this->connector(ConflictPolicy::FlagForReview);
        $b = $this->confirmConnectorMapping(SyncConnector::factory()->bound()->create([
            'owner_mda_id' => $this->mdaB->id,
            'source' => RegistrationSource::Socu,
            'conflict_policy' => ConflictPolicy::FlagForReview,
        ]));

        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Eze', 'nin' => '20000000014', 'id' => 'SHARED-ID']]);
        $this->engine()->runConnector($a, SyncTrigger::Scheduled);

        // A different MDA syncing a coincidentally identical source id must not be
        // treated as the same record — but the NIN still makes it a duplicate to flag,
        // not a silent second registration.
        $this->mockSocu([['first_name' => 'Ada', 'last_name' => 'Eze', 'nin' => '20000000014', 'id' => 'SHARED-ID']]);
        $run = $this->engine()->runConnector($b, SyncTrigger::Scheduled);

        $this->assertSame(0, $run->created_count);
        $this->assertSame(1, $run->flagged_count, "another MDA's identical record is a conflict, not an idempotent skip");
    }

    /* -------------------------------------------------------------- scheduling */

    public function test_the_scheduler_fans_out_only_to_enabled_connectors(): void
    {
        Queue::fake();

        $enabled = $this->connector(ConflictPolicy::FlagForReview, enabled: true);
        $this->connector(ConflictPolicy::FlagForReview, enabled: false);

        (new RunDueSyncConnectors)->handle();

        // A disabled connector is off — the scheduler must not reach an external system
        // an administrator has switched away from.
        Queue::assertPushed(RunSyncConnector::class, 1);
        Queue::assertPushed(
            RunSyncConnector::class,
            fn (RunSyncConnector $job): bool => $job->connectorId === $enabled->id,
        );
    }

    public function test_a_run_records_its_trigger_so_scheduled_and_manual_are_distinguishable(): void
    {
        $connector = $this->connector(ConflictPolicy::FlagForReview);
        $this->mockSocu([['first_name' => 'Sadiq', 'last_name' => 'Bala', 'nin' => '20000000015', 'id' => 'SOCU-T1']]);

        $scheduled = $this->engine()->runConnector($connector, SyncTrigger::Scheduled);
        $this->mockSocu([['first_name' => 'Sadiq', 'last_name' => 'Bala', 'nin' => '20000000015', 'id' => 'SOCU-T1']]);
        $manual = $this->engine()->runConnector($connector, SyncTrigger::Manual);

        $this->assertSame('scheduled', $scheduled->trigger->value);
        $this->assertSame('manual', $manual->trigger->value);
        $this->assertSame(2, SyncRun::query()->where('connector_id', $connector->id)->count());
    }

    /* ------------------------------------------------------------------ logging */

    public function test_every_record_is_logged_and_the_run_tally_matches_its_rows(): void
    {
        Beneficiary::factory()->withoutBvn()->create(['owner_mda_id' => $this->mdaB->id, 'nin' => '20000000016']);

        $connector = $this->connector(ConflictPolicy::FlagForReview);
        $this->mockSocu([
            ['first_name' => 'New', 'last_name' => 'Person', 'nin' => '20000000017', 'id' => 'R-CREATE'],
            ['first_name' => 'Dupe', 'last_name' => 'Person', 'nin' => '20000000016', 'id' => 'R-FLAG'],
            // Digits, wrong length → PRESENT but malformed, so the whole row is
            // rejected (FR-REG-05). See the normalisation test below for why a
            // non-numeric string is a different case.
            ['first_name' => 'Bad', 'last_name' => 'Row', 'nin' => '123', 'id' => 'R-REJECT'],
        ]);

        $run = $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        // The summary an administrator reads must add up to the per-row log beneath it.
        $this->assertSame(3, $run->fetched_count);
        $this->assertSame(1, $run->created_count);
        $this->assertSame(1, $run->flagged_count);
        $this->assertSame(1, $run->rejected_count);
        $this->assertSame(3, $run->rows()->count());

        $outcomes = $run->rows()->get()
            ->mapWithKeys(fn ($row): array => [$row->original_record_id => $row->outcome->value])
            ->all();
        $this->assertSame('created', $outcomes['R-CREATE']);
        $this->assertSame('flagged_conflict', $outcomes['R-FLAG']);
        $this->assertSame('rejected_identity', $outcomes['R-REJECT']);
    }

    public function test_a_row_log_explains_wh_y_a_record_was_flagged_or_rejected(): void
    {
        Beneficiary::factory()->withoutBvn()->create(['owner_mda_id' => $this->mdaB->id, 'nin' => '20000000018']);

        $connector = $this->connector(ConflictPolicy::LastWriteWins);
        $this->mockSocu([
            ['first_name' => 'Dupe', 'last_name' => 'Other', 'nin' => '20000000018', 'id' => 'W-FLAG'],
            ['first_name' => 'Bad', 'last_name' => 'Nin', 'nin' => '123', 'id' => 'W-REJECT'],
        ]);

        $run = $this->engine()->runConnector($connector, SyncTrigger::Scheduled);
        $rows = $run->rows()->get()->keyBy('original_record_id');

        // "Flagged" alone is not actionable; the administrator needs the reason.
        $this->assertSame('exact_match_other_mda', $rows['W-FLAG']->detail['reason'] ?? null);
        $this->assertNotEmpty($rows['W-REJECT']->detail['errors'] ?? []);
    }

    /**
     * Where the identity-rejection boundary actually falls, because it is not obvious
     * and sync is the path with no human looking at the file.
     *
     * The shared pipeline normalises identifiers to digits before validating, so a
     * non-numeric string reduces to NOTHING and is then indistinguishable from a field
     * the source simply did not send — which §9 says is valid for an optional NIN/BVN.
     * Only digits of the wrong length are "present but malformed".
     *
     * This is the Phase 2 rule, inherited rather than re-decided — but it means a source
     * emitting a placeholder like "N/A" registers a record with no NIN rather than
     * failing loudly. Flagged to the integration owner when a real connector is wired.
     */
    public function test_a_non_numeric_identifier_normalises_to_absent_not_malformed(): void
    {
        $connector = $this->connector(ConflictPolicy::FlagForReview);
        $this->mockSocu([
            ['first_name' => 'Placeholder', 'last_name' => 'Nin', 'nin' => 'N/A', 'id' => 'N-1'],
            ['first_name' => 'Short', 'last_name' => 'Nin', 'nin' => '123', 'id' => 'N-2'],
        ]);

        $run = $this->engine()->runConnector($connector, SyncTrigger::Scheduled);

        $this->assertSame(1, $run->created_count, '"N/A" reduces to no NIN, which is valid');
        $this->assertSame(1, $run->rejected_count, 'digits of the wrong length are malformed');
        $this->assertNull(Beneficiary::query()->where('idempotency_key', 'N-1')->firstOrFail()->nin);
    }

    public function test_a_row_log_never_carries_a_raw_identifier(): void
    {
        $connector = $this->connector(ConflictPolicy::FlagForReview);
        $this->mockSocu([['first_name' => 'Bad', 'last_name' => 'Nin', 'nin' => '12345678901x', 'id' => 'W-PII']]);

        $run = $this->engine()->runConnector($connector, SyncTrigger::Scheduled);
        $json = (string) json_encode($run->rows()->get()->pluck('detail')->all());

        // The log is read by administrators across MDAs — a rejection reason must not
        // become a place where the value that failed validation is stored in the clear.
        $this->assertStringNotContainsString('12345678901', $json);
    }
}
