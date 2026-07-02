<?php

declare(strict_types=1);

namespace App\Domain\Matching\Scoring\Comparators;

use App\Domain\Matching\Enums\MatchComparator;
use InvalidArgumentException;

/**
 * Resolves a {@see Comparator} for a configured comparator key. New comparators
 * are added by registering them here — the scorer never changes.
 */
class ComparatorRegistry
{
    /** @var array<string, Comparator> */
    private array $comparators;

    public function __construct()
    {
        $this->comparators = [
            MatchComparator::Exact->value => new ExactComparator,
            MatchComparator::JaroWinkler->value => new JaroWinklerComparator,
            MatchComparator::Levenshtein->value => new LevenshteinComparator,
            MatchComparator::Phonetic->value => new PhoneticComparator,
            MatchComparator::DateProximity->value => new DateProximityComparator,
        ];
    }

    public function get(string $comparator): Comparator
    {
        return $this->comparators[$comparator]
            ?? throw new InvalidArgumentException("Unknown match comparator: {$comparator}");
    }
}
