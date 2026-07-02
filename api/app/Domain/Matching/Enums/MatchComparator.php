<?php

declare(strict_types=1);

namespace App\Domain\Matching\Enums;

/**
 * The built-in field comparators a fuzzy rule may use (PRD FR-DUP-03). Each maps
 * to a Comparator implementation returning a 0..1 similarity.
 */
enum MatchComparator: string
{
    case Exact = 'exact';
    case JaroWinkler = 'jaro_winkler';
    case Levenshtein = 'levenshtein';
    // Phonetic (Hausa/Nigerian name variance) blended with edit distance.
    case Phonetic = 'phonetic';
    // Date-of-birth proximity (tolerant of typos) rather than exact equality.
    case DateProximity = 'date_proximity';
}
