<?php

declare(strict_types=1);

namespace App\Domain\Registry\Events;

use App\Domain\Registry\Models\ImportBatch;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Screening finished and flagged at least one row against an existing record
 * (PRD FR-DUP-01/05). Fired once per batch when the preview becomes available, never
 * per row — a 900-row file with 40 matches is one piece of news, not forty.
 *
 * Carries counts only. The recipient is told how many rows need a decision and where to
 * make it; no identity data rides on the event, so the notification can be delivered by
 * any channel without leaking PII (SECURITY.md — minimise PII off-platform).
 */
class ImportDuplicatesSurfaced
{
    use Dispatchable;

    public function __construct(
        public readonly ImportBatch $batch,
        /** Rows matched on a unique identifier — definitive duplicates. */
        public readonly int $exactCount,
        /** Fuzzy matches needing a same-person judgement. */
        public readonly int $probableCount,
    ) {}

    public function total(): int
    {
        return $this->exactCount + $this->probableCount;
    }
}
