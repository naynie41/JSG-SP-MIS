<?php

declare(strict_types=1);

namespace App\Domain\Reference\Imports;

/**
 * What a dataset load actually did. Returned rather than logged so the artisan command
 * and the seeder can both report it, and so tests can assert on it.
 *
 * `staleWards` is reported but never deleted — see {@see AdministrativeDivisionLoader}.
 */
final readonly class DivisionLoadResult
{
    /**
     * @param  array<string, int>  $wardsPerLga  lga code => ward count in this dataset
     * @param  list<string>  $staleWards  "lga_code/ward_code" rows in the DB but absent from this file
     * @param  list<string>  $lgasWithoutWards  lga codes the dataset gave no wards for
     */
    public function __construct(
        public int $lgasCreated,
        public int $lgasUpdated,
        public int $wardsCreated,
        public int $wardsUpdated,
        public array $wardsPerLga,
        public array $staleWards,
        public array $lgasWithoutWards,
    ) {}

    public function totalWards(): int
    {
        return $this->wardsCreated + $this->wardsUpdated;
    }

    public function totalLgas(): int
    {
        return $this->lgasCreated + $this->lgasUpdated;
    }
}
