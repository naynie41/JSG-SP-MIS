<?php

declare(strict_types=1);

namespace App\Domain\Matching\Scoring\Comparators;

use App\Domain\Matching\Support\PhoneticEncoder;

/**
 * Name similarity that blends phonetics with edit distance (PRD FR-DUP-03): a
 * phonetic-code match scores 1.0 (handles Hausa/Nigerian spelling variance);
 * otherwise it falls back to Jaro-Winkler so near-misses still score.
 */
class PhoneticComparator implements Comparator
{
    public function __construct(
        private readonly PhoneticEncoder $encoder = new PhoneticEncoder,
        private readonly JaroWinklerComparator $fallback = new JaroWinklerComparator,
    ) {}

    public function compare(?string $a, ?string $b): float
    {
        if ($a === null || $b === null || $a === '' || $b === '') {
            return 0.0;
        }
        if ($a === $b || $this->encoder->code($a) === $this->encoder->code($b)) {
            return 1.0;
        }

        return $this->fallback->compare($a, $b);
    }
}
