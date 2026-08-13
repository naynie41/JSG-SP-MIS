<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Registry\Enums\ImportRowResolution;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Administration-console demo data — enough for EVERY console section to render
 * something real in a local/staging stack.
 *
 * It deliberately seeds only what no existing seeder covers, and reuses the rest:
 * roles/permissions come from {@see RolesAndPermissionsSeeder}, the administrator from
 * {@see DevUserSeeder}, MDAs from {@see SampleMdaSeeder}, MDA staff from
 * {@see SampleMdaUserSeeder}, the registry from {@see RegistrySampleSeeder},
 * programmes from {@see ProgrammeSampleSeeder}, and partners/funded programmes from
 * {@see PartnerDemoSeeder}. What this adds is the missing piece — IMPORT BATCHES with
 * duplicate-review outcomes — plus a couple of account states (suspended, MFA-less) so
 * User & Access and Registry & Data Quality are not uniformly green.
 *
 * Never runs in production. Idempotent: safe to re-run.
 */
class AdminConsoleDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            return;
        }

        $this->call([
            RolesAndPermissionsSeeder::class,
            SampleMdaSeeder::class,
            DevUserSeeder::class,
            SampleMdaUserSeeder::class,
            ProgrammeSampleSeeder::class,
            RegistrySampleSeeder::class,
            PartnerDemoSeeder::class,
        ]);

        $this->seedAccountStates();
        $this->seedImportHistory();
    }

    /**
     * A suspended account and an MFA-less privileged account, so the console's access
     * KPIs and alerts have something other than a clean bill of health to show.
     */
    private function seedAccountStates(): void
    {
        $mda = Mda::query()->withoutGlobalScopes()->orderBy('name')->first();
        if ($mda === null) {
            return;
        }

        $officer = Role::query()->where('key', RoleKey::MdaOfficer->value)->value('id');

        User::withoutEvents(function () use ($mda, $officer): void {
            User::updateOrCreate(
                ['email' => 'suspended.officer@spmis.local'],
                [
                    'name' => 'Suspended Officer',
                    'password' => 'ChangeMe!Demo12345',
                    'role_id' => $officer,
                    'mda_id' => $mda->id,
                    'status' => UserStatus::Suspended,
                    'email_verified_at' => now(),
                ],
            );

            User::updateOrCreate(
                ['email' => 'no.mfa.officer@spmis.local'],
                [
                    'name' => 'Officer Without MFA',
                    'password' => 'ChangeMe!Demo12345',
                    'role_id' => $officer,
                    'mda_id' => $mda->id,
                    'status' => UserStatus::Active,
                    'mfa_enabled' => false,
                    'email_verified_at' => now(),
                ],
            );
        });
    }

    /**
     * Import batches across sources and outcomes, with duplicate-review rows, so
     * Registry & Data Quality (duplicate rate, validation results), Integrations
     * (import logs) and the `duplicates`/`imports` report datasets all have data.
     */
    private function seedImportHistory(): void
    {
        $mdas = Mda::query()->withoutGlobalScopes()->orderBy('name')->take(2)->get();
        if ($mdas->isEmpty()) {
            return;
        }

        $uploader = User::query()->withoutGlobalScope(MdaScope::class)
            ->whereNotNull('mda_id')->value('id');

        $batches = [
            ['file' => 'kano-road-cash-transfer-jan.xlsx', 'source' => 'excel', 'status' => 'completed', 'total' => 480, 'valid' => 465, 'invalid' => 15, 'committed' => 465, 'days' => 26],
            ['file' => 'dutse-school-feeding-feb.csv', 'source' => 'csv', 'status' => 'completed', 'total' => 312, 'valid' => 298, 'invalid' => 14, 'committed' => 298, 'days' => 18],
            ['file' => 'hadejia-kobo-intake.xlsx', 'source' => 'kobo', 'status' => 'preview_ready', 'total' => 150, 'valid' => 131, 'invalid' => 19, 'committed' => 0, 'days' => 6],
            ['file' => 'birnin-kudu-partial.csv', 'source' => 'csv', 'status' => 'failed', 'total' => 90, 'valid' => 0, 'invalid' => 90, 'committed' => 0, 'days' => 2],
        ];

        foreach ($batches as $i => $spec) {
            $mda = $mdas[$i % $mdas->count()];

            /** @var ImportBatch $batch */
            $batch = ImportBatch::withoutEvents(fn (): ImportBatch => ImportBatch::updateOrCreate(
                ['original_filename' => $spec['file']],
                [
                    'owner_mda_id' => $mda->id,
                    'uploaded_by' => $uploader,
                    'stored_path' => 'imports/demo/'.$spec['file'],
                    'source' => $spec['source'],
                    'status' => $spec['status'],
                    'total_rows' => $spec['total'],
                    'valid_rows' => $spec['valid'],
                    'invalid_rows' => $spec['invalid'],
                    'committed_rows' => $spec['committed'],
                    'error' => $spec['status'] === 'failed' ? 'Every row failed identity validation.' : null,
                    'created_at' => Carbon::now()->subDays((int) $spec['days']),
                    'updated_at' => Carbon::now()->subDays((int) $spec['days']),
                ],
            ));

            $this->seedRowsFor($batch);
        }
    }

    /**
     * Duplicate-review rows for a batch: a mix of match bands and resolutions so the
     * duplicate rate and its resolved share are both meaningful. The payload holds no
     * real identity data — these are synthetic reference numbers only.
     */
    private function seedRowsFor(ImportBatch $batch): void
    {
        if (ImportRow::query()->where('import_batch_id', $batch->id)->exists()) {
            return; // idempotent
        }

        // Real domain values only. `match_band` and `resolution` are plain string
        // columns, so an invented value ('possible', 'merged', 'kept_separate') persists
        // silently and then renders as an unknown label — or worse, is counted as a band
        // that does not exist. MatchBand is exact|probable|none; ImportRowResolution is
        // new|link|skip.
        $plan = [
            ['band' => MatchBand::Exact->value, 'resolution' => ImportRowResolution::Link->value, 'count' => 4],
            ['band' => MatchBand::Exact->value, 'resolution' => null, 'count' => 2],
            ['band' => MatchBand::Probable->value, 'resolution' => ImportRowResolution::New->value, 'count' => 3],
            ['band' => MatchBand::Probable->value, 'resolution' => null, 'count' => 3],
            ['band' => MatchBand::Probable->value, 'resolution' => ImportRowResolution::Skip->value, 'count' => 2],
            ['band' => MatchBand::None->value, 'resolution' => null, 'count' => 6],
        ];

        $rowNumber = 1;
        $rows = [];
        foreach ($plan as $group) {
            for ($i = 0; $i < (int) $group['count']; $i++) {
                $rows[] = [
                    'import_batch_id' => $batch->id,
                    'row_number' => $rowNumber,
                    'original_record_id' => sprintf('%s-%04d', strtoupper(substr($batch->source->value, 0, 3)), $rowNumber),
                    'payload' => ['reference' => sprintf('DEMO-%s-%04d', $batch->id, $rowNumber)],
                    'is_valid' => true,
                    'match_band' => $group['band'],
                    'resolution' => $group['resolution'],
                    'created_at' => $batch->created_at,
                    'updated_at' => $batch->created_at,
                ];
                $rowNumber++;
            }
        }

        foreach ($rows as $row) {
            ImportRow::withoutEvents(fn () => ImportRow::create($row));
        }
    }
}
