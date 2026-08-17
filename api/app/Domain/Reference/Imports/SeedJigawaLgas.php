<?php

declare(strict_types=1);

namespace App\Domain\Reference\Imports;

use App\Domain\Reference\Models\Lga;
use App\Domain\Reference\Services\ReferenceDataCache;
use App\Domain\Registry\Enums\Lga as LgaEnum;
use Illuminate\Console\Command;

/**
 * Seeds the 27 Jigawa LGAs — and NO wards — from {@see LgaEnum}:
 *
 *   php artisan reference:seed-lgas
 *
 * This is NOT a substitute for `reference:load-divisions`. It exists because the LGA list
 * and the ward list have different provenance:
 *
 *  - The 27 LGAs are already committed, authoritative reference data. They are the values
 *    `beneficiaries.lga` is validated against (FR-REG-04/05, a locked decision), and
 *    `AdministrativeDivisionLoader` checks supplied files against this same enum. Writing
 *    them into `lgas` copies a fact the repository already asserts.
 *  - Ward names are NOT in this repository and are never generated. They come only from a
 *    maintainer-supplied dataset.
 *
 * So this command makes the legitimate intermediate state reachable: LGAs known, wards not
 * yet supplied. That state is what the activity location set needs in order to record
 * whole-LGA coverage, and what the activity-location backfill needs in order to resolve
 * existing LGA values instead of refusing to run.
 *
 * Idempotent. Never touches wards. Once a real dataset arrives,
 * `reference:load-divisions` updates these rows in place (it matches on the same `code`)
 * and adds the wards.
 */
class SeedJigawaLgas extends Command
{
    protected $signature = 'reference:seed-lgas';

    protected $description = 'Seed the 27 Jigawa LGAs from the committed enum (no wards — those need a dataset)';

    public function handle(ReferenceDataCache $cache): int
    {
        $created = 0;
        $updated = 0;

        foreach (LgaEnum::cases() as $case) {
            $lga = Lga::query()->firstOrNew(['code' => $case->value]);
            $existed = $lga->exists;

            $lga->name = $case->label();
            $lga->state = 'Jigawa';
            $lga->save();

            $existed ? $updated++ : $created++;
        }

        $cache->flush();

        $this->info("Seeded 27 Jigawa LGAs ({$created} new, {$updated} updated). No wards were created.");
        $this->line('Ward data requires an authoritative dataset: php artisan reference:load-divisions');

        return self::SUCCESS;
    }
}
