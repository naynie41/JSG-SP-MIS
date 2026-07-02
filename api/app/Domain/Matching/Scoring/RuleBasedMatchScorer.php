<?php

declare(strict_types=1);

namespace App\Domain\Matching\Scoring;

use App\Domain\Matching\Contracts\MatchScorer;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Scoring\Comparators\ComparatorRegistry;

/**
 * The built-in deterministic + fuzzy scorer (PRD FR-DUP-03).
 *
 * Deterministic: each configured key set (e.g. `[nin]`, `[bvn]`) is a match when
 * every field in the set is present and equal on both records — one hit flags the
 * score as deterministic. Fuzzy: each weighted field is compared with its
 * comparator; the composite is the weighted-average similarity, normalised by the
 * total configured weight, so it stays in `[0,1]` regardless of how weights are set.
 */
class RuleBasedMatchScorer implements MatchScorer
{
    public function __construct(
        private readonly ComparatorRegistry $comparators,
        private readonly FieldNormalizer $normalizer,
    ) {}

    public function score(array $candidate, array $existing, MatchingConfig $config): MatchScore
    {
        $explanation = [];
        $deterministic = false;

        foreach ($config->deterministicKeySets() as $fields) {
            $matched = $this->keySetMatches($fields, $candidate, $existing);
            $explanation[] = ['type' => 'deterministic', 'fields' => $fields, 'matched' => $matched];
            $deterministic = $deterministic || $matched;
        }

        $weightedSum = 0.0;
        foreach ($config->fuzzyFields() as $rule) {
            $field = (string) $rule['field'];
            $comparator = (string) $rule['comparator'];
            $weight = (float) $rule['weight'];

            $a = $this->normalizer->normalize($field, $candidate[$field] ?? null);
            $b = $this->normalizer->normalize($field, $existing[$field] ?? null);
            $similarity = $this->comparators->get($comparator)->compare($a, $b);
            $contribution = $weight * $similarity;
            $weightedSum += $contribution;

            $explanation[] = [
                'type' => 'fuzzy',
                'field' => $field,
                'comparator' => $comparator,
                'weight' => $weight,
                'similarity' => round($similarity, 4),
                'contribution' => round($contribution, 4),
                'present' => $a !== null && $b !== null,
            ];
        }

        $totalWeight = $config->totalFuzzyWeight();
        $composite = $totalWeight > 0.0 ? $weightedSum / $totalWeight : 0.0;

        return new MatchScore($composite, $deterministic, $explanation);
    }

    /**
     * @param  list<string>  $fields
     * @param  array<string, mixed>  $candidate
     * @param  array<string, mixed>  $existing
     */
    private function keySetMatches(array $fields, array $candidate, array $existing): bool
    {
        if ($fields === []) {
            return false;
        }

        foreach ($fields as $field) {
            $a = $this->normalizer->normalize($field, $candidate[$field] ?? null);
            $b = $this->normalizer->normalize($field, $existing[$field] ?? null);
            if ($a === null || $b === null || $a !== $b) {
                return false;
            }
        }

        return true;
    }
}
