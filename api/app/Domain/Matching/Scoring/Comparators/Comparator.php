<?php

declare(strict_types=1);

namespace App\Domain\Matching\Scoring\Comparators;

/**
 * Compares two already-normalised field values and returns a similarity in
 * `[0, 1]` (0 = no similarity, 1 = identical). A null/empty value on either side
 * yields 0 — a missing value is never evidence of a match.
 */
interface Comparator
{
    public function compare(?string $a, ?string $b): float;
}
