<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Reference\Imports\AdministrativeDivisionLoader;
use App\Domain\Reference\Imports\ReferenceDatasetException;
use Illuminate\Database\Seeder;

/**
 * Seeds LGA/Ward reference data from the maintainer-supplied authoritative dataset.
 *
 * Deliberately NOT part of {@see DatabaseSeeder}. `db:seed` must keep working on a
 * fresh clone, and a fresh clone has no dataset file — wiring this in would make the
 * baseline seed fail for everyone, which is the fastest route to someone "fixing" it
 * with a hardcoded ward list. Run it explicitly once the file is in place:
 *
 *   php artisan db:seed --class=AdministrativeDivisionsSeeder
 *   php artisan reference:load-divisions [path]   (equivalent, with a fuller report)
 *
 * Idempotent — upserts, so re-running with a corrected file updates in place.
 *
 * @throws ReferenceDatasetException when the dataset is absent or not credibly Jigawa's
 */
class AdministrativeDivisionsSeeder extends Seeder
{
    public function run(AdministrativeDivisionLoader $loader): void
    {
        // No try/catch: the exception IS the loud failure. Swallowing it here would
        // leave an empty lookup table behind a "seeded successfully" message.
        $result = $loader->loadFromFile();

        $this->command?->info(sprintf(
            'Loaded %d LGAs and %d wards (%d new, %d updated).',
            $result->totalLgas(),
            $result->totalWards(),
            $result->wardsCreated,
            $result->wardsUpdated,
        ));

        if ($result->lgasWithoutWards !== []) {
            $this->command?->warn(
                'These LGAs have no wards in the dataset: '.implode(', ', $result->lgasWithoutWards)
            );
        }
    }
}
