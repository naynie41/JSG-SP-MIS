<?php

declare(strict_types=1);

namespace App\Domain\Reference\Imports;

use Illuminate\Console\Command;

/**
 * Loads Jigawa LGA/Ward reference data from an authoritative dataset file:
 *
 *   php artisan reference:load-divisions
 *   php artisan reference:load-divisions storage/app/reference/jigawa-wards.csv
 *
 * With no argument it reads `config('reference.divisions.path')`. Idempotent.
 * Fails loudly (non-zero exit, explanatory message) rather than seeding placeholders.
 */
class LoadAdministrativeDivisions extends Command
{
    protected $signature = 'reference:load-divisions {file? : path to the CSV/JSON dataset}';

    protected $description = 'Load Jigawa LGA/Ward reference data from an authoritative dataset file';

    public function handle(AdministrativeDivisionLoader $loader): int
    {
        $file = $this->argument('file');

        try {
            $result = $loader->loadFromFile(is_string($file) ? $file : null);
        } catch (ReferenceDatasetException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info(sprintf(
            'Loaded %d LGAs (%d new) and %d wards (%d new, %d updated).',
            $result->totalLgas(),
            $result->lgasCreated,
            $result->totalWards(),
            $result->wardsCreated,
            $result->wardsUpdated,
        ));

        // The maintainer should eyeball the per-LGA spread — this is the check that a
        // ward TOTAL cannot give, since a plausible total can hide an empty LGA.
        $this->table(
            ['LGA', 'Wards'],
            collect($result->wardsPerLga)->map(fn (int $n, string $code): array => [$code, $n])->values()->all(),
        );

        if ($result->lgasWithoutWards !== []) {
            $this->warn('No wards supplied for: '.implode(', ', $result->lgasWithoutWards));
        }

        if ($result->staleWards !== []) {
            $this->warn(sprintf(
                '%d stored ward(s) are absent from this dataset and were left in place: %s',
                count($result->staleWards),
                implode(', ', array_slice($result->staleWards, 0, 20)).(count($result->staleWards) > 20 ? ' …' : ''),
            ));
        }

        return self::SUCCESS;
    }
}
