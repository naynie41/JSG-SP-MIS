<?php

declare(strict_types=1);

namespace App\Domain\Matching\Enums;

/**
 * The three outcome bands of a duplicate check (PRD FR-DUP-03).
 *
 * - Exact    — a deterministic key set matched (e.g. NIN) or the score cleared
 *              the optional auto-accept threshold; treat as the same person.
 * - Probable — the fuzzy score cleared the review threshold; needs human review.
 * - None     — below the review threshold; not a match.
 */
enum MatchBand: string
{
    case Exact = 'exact';
    case Probable = 'probable';
    case None = 'none';

    /** Higher = more confident; used to rank results. */
    public function rank(): int
    {
        return match ($this) {
            self::Exact => 2,
            self::Probable => 1,
            self::None => 0,
        };
    }
}
