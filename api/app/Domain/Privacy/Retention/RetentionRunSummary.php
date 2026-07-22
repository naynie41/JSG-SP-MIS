<?php

declare(strict_types=1);

namespace App\Domain\Privacy\Retention;

/**
 * Tallies what one retention run did (or, in a dry run, WOULD do) per action
 * (NFR-PRV-01). Returned to the caller and written to the run's audit entry.
 */
class RetentionRunSummary
{
    public int $flagged = 0;

    public int $aggregated = 0;

    public int $anonymized = 0;

    public int $deleted = 0;

    public function __construct(
        public readonly bool $dryRun = false,
        public bool $ranPolicies = true,
    ) {}

    public function total(): int
    {
        return $this->flagged + $this->aggregated + $this->anonymized + $this->deleted;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'dry_run' => $this->dryRun,
            'ran_policies' => $this->ranPolicies,
            'flagged' => $this->flagged,
            'aggregated' => $this->aggregated,
            'anonymized' => $this->anonymized,
            'deleted' => $this->deleted,
            'total' => $this->total(),
        ];
    }
}
