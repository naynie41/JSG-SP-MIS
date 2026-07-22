<?php

declare(strict_types=1);

namespace App\Domain\Sync\Enums;

/**
 * The outcome of a single synced record after it ran the SAME pipeline as import
 * (validation → dedup → ownership/provenance → idempotency).
 */
enum SyncRowOutcome: string
{
    case Created = 'created';                   // new beneficiary saved (no match)
    case Updated = 'updated';                   // existing owned record updated (last-write-wins)
    case SkippedIdempotent = 'skipped_idempotent'; // already synced (same idempotency key) — no change
    case FlaggedConflict = 'flagged_conflict';  // matched an existing record; left for admin review
    case RejectedIdentity = 'rejected_identity'; // malformed identity field — never saved (FR-REG-05)
    case Error = 'error';                        // unexpected failure processing the record

    /** Which run-summary counter this outcome increments. */
    public function counter(): string
    {
        return match ($this) {
            self::Created => 'created_count',
            self::Updated => 'updated_count',
            self::SkippedIdempotent => 'skipped_count',
            self::FlaggedConflict => 'flagged_count',
            self::RejectedIdentity => 'rejected_count',
            self::Error => 'error_count',
        };
    }
}
