<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Models\Mda;
use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Activity;
use App\Domain\Registry\Enums\Lga;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Services\DashboardSnapshotService;
use App\Domain\Sync\Models\SyncConnector;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Phase 6E executive demo data (PRD FR-RPT-01/02/03) — enough synthetic, cross-module
 * data that EVERY tab, chart, map, insight, alert AND projection renders meaningfully.
 *
 * It chains the existing sample seeders (MDAs, registry + households, programmes +
 * activities + benefits, a cross-MDA double-dipping case, referrals/grievances, a funded
 * partner + LGA boundaries) and then adds the Phase 6E-specific richness the others lack:
 *
 *  - a RISING 10-month history of registrations + deliveries (so trends AND the linear
 *    forecasts have a slope to project);
 *  - data-sharing / sync health (connectors + runs);
 *  - an import-matched DUPLICATE case (so the registry-quality duplicate alert fires).
 *
 * LOCAL/STAGING ONLY — never real PII (all factory-generated), never production. Idempotent.
 */
class ExecutiveDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $this->call([
            SampleMdaSeeder::class,
            RegistrySampleSeeder::class,
            ProgrammeSampleSeeder::class,
            ReferralSampleSeeder::class,
            GrievanceSampleSeeder::class,
            ReportingSampleSeeder::class,
        ]);

        $this->seedHistoricalTrend();
        $this->seedSyncHealth();
        $this->seedImportDuplicate();

        // Re-warm snapshots so the unfiltered dashboards reflect the added history.
        app(DashboardSnapshotService::class)->refreshAll();
    }

    /**
     * A rising 10-month series of registrations + verified deliveries against the
     * existing programmes' activities — the history the trend charts and the linear
     * projections (beneficiary growth, registration growth, budget runway) need.
     */
    private function seedHistoricalTrend(): void
    {
        // Idempotent: only backfill once (nothing older than ~2 months yet).
        if (Benefit::query()->whereDate('delivery_date', '<', Carbon::now()->subMonths(2)->toDateString())->exists()) {
            return;
        }

        $activities = Activity::query()
            ->get(['id', 'programme_id', 'owner_mda_id'])
            ->unique('programme_id')
            ->take(3)
            ->values();
        if ($activities->isEmpty()) {
            return;
        }

        $lgas = array_map(fn (Lga $l): string => $l->value, Lga::cases());

        foreach (range(10, 1) as $monthsAgo) {
            $month = Carbon::now()->subMonths($monthsAgo);
            $count = max(1, 11 - $monthsAgo); // rising: fewer long ago, more recently

            foreach ($activities as $idx => $activity) {
                for ($k = 0; $k < $count; $k++) {
                    $lga = $lgas[($monthsAgo + $k + $idx) % count($lgas)];
                    $ward = 'Ward '.(1 + (($monthsAgo + $k) % 8));

                    $beneficiary = Beneficiary::factory()->create([
                        'owner_mda_id' => $activity->owner_mda_id,
                        'lga' => $lga,
                        'ward' => $ward,
                        'registration_date' => $month->copy()->day(min(27, 1 + $k))->toDateString(),
                    ]);

                    Benefit::factory()->create([
                        'beneficiary_id' => $beneficiary->id,
                        'programme_id' => $activity->programme_id,
                        'activity_id' => $activity->id,
                        'mda_id' => $activity->owner_mda_id,
                        'monetary_value' => 400_000 + $k * 15_000,
                        'lga' => $lga,
                        'ward' => $ward,
                        'delivery_date' => $month->copy()->day(min(28, 2 + $k))->toDateString(),
                        'status' => BenefitStatus::Verified,
                    ]);
                }
            }
        }
    }

    /** Data-sharing / API-sync health for the Coordination tab: connectors + runs. */
    private function seedSyncHealth(): void
    {
        if (DB::table('sync_runs')->exists()) {
            return;
        }

        $mdas = Mda::query()->take(2)->get();
        $sources = ['socu', 'government_system'];

        foreach ($mdas as $i => $mda) {
            SyncConnector::factory()->create(['owner_mda_id' => $mda->id, 'source' => $sources[$i % count($sources)]]);

            foreach (['completed', 'completed', 'failed'] as $j => $status) {
                DB::table('sync_runs')->insert([
                    'id' => (string) Str::uuid(),
                    'trigger' => 'scheduled',
                    'source' => $sources[$i % count($sources)],
                    'owner_mda_id' => $mda->id,
                    'conflict_policy' => 'flag_for_review',
                    'status' => $status,
                    'created_at' => Carbon::now()->subDays($j),
                    'updated_at' => Carbon::now()->subDays($j),
                ]);
            }
        }
    }

    /** An import batch with matched rows → a surfaced DUPLICATE (registry-quality alert). */
    private function seedImportDuplicate(): void
    {
        if (DB::table('import_rows')->whereIn('match_band', ['exact', 'probable'])->exists()) {
            return;
        }

        $activity = Activity::query()->first();
        if ($activity === null) {
            return;
        }

        $batchId = (string) Str::uuid();
        DB::table('import_batches')->insert([
            'id' => $batchId,
            'owner_mda_id' => $activity->owner_mda_id,
            'original_filename' => 'sample-import.csv',
            'stored_path' => 'imports/sample-import.csv',
            'source' => 'csv',
            'activity_id' => $activity->id,
            'status' => 'completed',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        foreach ([['exact', 'link'], ['probable', 'new']] as $i => [$band, $resolution]) {
            DB::table('import_rows')->insert([
                'id' => (string) Str::uuid(),
                'import_batch_id' => $batchId,
                'row_number' => $i + 1,
                'payload' => json_encode([]),
                'is_valid' => true,
                'match_band' => $band,
                'resolution' => $resolution,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
