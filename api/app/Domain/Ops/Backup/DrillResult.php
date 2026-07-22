<?php

declare(strict_types=1);

namespace App\Domain\Ops\Backup;

/**
 * The outcome of a restore drill (NFR-AVAIL-01): whether a real backup could be
 * restored and verified within the RTO, with the row-count comparison that proves
 * the restored copy matches the source.
 */
final class DrillResult
{
    /**
     * @param  array<string, int>  $sourceCounts
     * @param  array<string, int>  $restoredCounts
     */
    public function __construct(
        public readonly bool $passed,
        public readonly bool $countsMatch,
        public readonly bool $withinRto,
        public readonly float $durationSeconds,
        public readonly int $rtoMinutes,
        public readonly int $rpoMinutes,
        public readonly array $sourceCounts,
        public readonly array $restoredCounts,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'passed' => $this->passed,
            'counts_match' => $this->countsMatch,
            'within_rto' => $this->withinRto,
            'duration_seconds' => round($this->durationSeconds, 2),
            'rto_minutes' => $this->rtoMinutes,
            'rpo_minutes' => $this->rpoMinutes,
            'tables_verified' => count($this->sourceCounts),
        ];
    }
}
