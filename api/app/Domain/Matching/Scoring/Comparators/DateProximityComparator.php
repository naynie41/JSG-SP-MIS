<?php

declare(strict_types=1);

namespace App\Domain\Matching\Scoring\Comparators;

/**
 * Date-of-birth proximity (PRD FR-DUP-03): exact dates score 1.0; nearby dates
 * (typos, transposed digits) decay linearly to 0 over a ~3-year window, so a
 * plausible DOB typo still contributes to the score rather than zeroing it.
 */
class DateProximityComparator implements Comparator
{
    private const WINDOW_DAYS = 1095;

    public function compare(?string $a, ?string $b): float
    {
        if ($a === null || $b === null || $a === '' || $b === '') {
            return 0.0;
        }
        if ($a === $b) {
            return 1.0;
        }

        $timeA = strtotime($a);
        $timeB = strtotime($b);
        if ($timeA === false || $timeB === false) {
            return 0.0;
        }

        $diffDays = abs($timeA - $timeB) / 86400;

        return max(0.0, 1.0 - $diffDays / self::WINDOW_DAYS);
    }
}
