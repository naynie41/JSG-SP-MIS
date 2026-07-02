<?php

declare(strict_types=1);

namespace App\Domain\Matching\Scoring\Comparators;

/** Exact equality on the normalised values (1.0 or 0.0). */
class ExactComparator implements Comparator
{
    public function compare(?string $a, ?string $b): float
    {
        if ($a === null || $b === null || $a === '' || $b === '') {
            return 0.0;
        }

        return $a === $b ? 1.0 : 0.0;
    }
}
