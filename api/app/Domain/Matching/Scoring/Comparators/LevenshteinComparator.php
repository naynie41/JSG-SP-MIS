<?php

declare(strict_types=1);

namespace App\Domain\Matching\Scoring\Comparators;

/**
 * Normalised Levenshtein similarity: `1 - distance / maxLength` in `[0, 1]`.
 */
class LevenshteinComparator implements Comparator
{
    public function compare(?string $a, ?string $b): float
    {
        if ($a === null || $b === null || $a === '' || $b === '') {
            return 0.0;
        }
        if ($a === $b) {
            return 1.0;
        }

        // Both values are non-empty here, so the max length is at least 1.
        $max = max(mb_strlen($a), mb_strlen($b));

        // levenshtein() is byte-based; adequate for normalised Latin names.
        $distance = levenshtein($a, $b);

        return max(0.0, 1.0 - $distance / $max);
    }
}
