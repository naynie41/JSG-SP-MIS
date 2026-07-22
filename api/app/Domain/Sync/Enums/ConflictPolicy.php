<?php

declare(strict_types=1);

namespace App\Domain\Sync\Enums;

/**
 * How a sync run resolves a record that matches an existing beneficiary (FR-DSH-02).
 * Explicit + configurable per connector; cross-MDA-owned matches are always flagged
 * regardless (an MDA's record is never silently overwritten by another source).
 */
enum ConflictPolicy: string
{
    case LastWriteWins = 'last_write_wins'; // the source overwrites the record IT owns
    case FlagForReview = 'flag_for_review'; // never modify; raise a flagged_conflict
}
