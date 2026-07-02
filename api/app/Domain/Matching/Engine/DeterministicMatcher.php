<?php

declare(strict_types=1);

namespace App\Domain\Matching\Engine;

use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Scoring\FieldNormalizer;
use App\Domain\Matching\Scoring\MatchScore;

/**
 * Deterministic (exact-key) matching (PRD FR-DUP-03). Surfaces an `exact`-band
 * result whenever every field of a configured key set (default `[nin]`, `[bvn]`)
 * is present and equal on both records — citing the key(s) that fired.
 *
 * This works in memory, so it serves both **within-batch** dedup (the candidate
 * against other not-yet-persisted records) and confirmation of the index-backed
 * registry candidates. It NEVER blocks: it only returns what it finds — enforcing
 * is the caller's decision (the "never hard-block" rule).
 */
class DeterministicMatcher
{
    public function __construct(private readonly FieldNormalizer $normalizer) {}

    /**
     * @param  array<string, mixed>  $candidate
     * @param  iterable<array<string, mixed>>  $existing  each may carry an `id`
     * @return list<MatchResult> exact matches only
     */
    public function match(array $candidate, iterable $existing, MatchingConfig $config): array
    {
        $keySets = $config->deterministicKeySets();
        $normalisedCandidate = $this->normaliseCandidateKeys($candidate, $keySets);

        $results = [];
        foreach ($existing as $record) {
            $fired = $this->firedKeySets($keySets, $normalisedCandidate, $record);
            if ($fired === []) {
                continue;
            }

            $reference = isset($record['id']) ? (string) $record['id'] : null;
            $explanation = array_map(
                static fn (array $keySet): array => ['type' => 'deterministic', 'fields' => $keySet, 'matched' => true],
                $fired,
            );

            $results[] = new MatchResult($reference, MatchBand::Exact, new MatchScore(1.0, true, $explanation));
        }

        return $results;
    }

    /**
     * @param  list<list<string>>  $keySets
     * @param  array<string, string|null>  $normalisedCandidate
     * @param  array<string, mixed>  $record
     * @return list<list<string>> the key sets that matched
     */
    private function firedKeySets(array $keySets, array $normalisedCandidate, array $record): array
    {
        $fired = [];
        foreach ($keySets as $keySet) {
            if ($keySet === []) {
                continue;
            }

            $matched = true;
            foreach ($keySet as $field) {
                $a = $normalisedCandidate[$field] ?? null;
                $b = $this->normalizer->normalize($field, $record[$field] ?? null);
                if ($a === null || $b === null || $a !== $b) {
                    $matched = false;
                    break;
                }
            }

            if ($matched) {
                $fired[] = $keySet;
            }
        }

        return $fired;
    }

    /**
     * @param  array<string, mixed>  $candidate
     * @param  list<list<string>>  $keySets
     * @return array<string, string|null>
     */
    private function normaliseCandidateKeys(array $candidate, array $keySets): array
    {
        $normalised = [];
        foreach ($keySets as $keySet) {
            foreach ($keySet as $field) {
                if (! array_key_exists($field, $normalised)) {
                    $normalised[$field] = $this->normalizer->normalize($field, $candidate[$field] ?? null);
                }
            }
        }

        return $normalised;
    }
}
