<?php

declare(strict_types=1);

namespace App\Domain\Matching\Engine;

use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Scoring\FieldNormalizer;

/**
 * The ordered duplicate-matching cascade (PRD §9 locked decision). It evaluates a
 * candidate against a pool of existing records in the configured order —
 * **exact NIN → exact BVN → fuzzy name/phone** by default — and STOPS at the first
 * exact (deterministic) stage that fires. The stages, keys, weights and thresholds
 * all come from the {@see MatchingConfig}; nothing is hard-coded here.
 *
 * Rules:
 * - Deterministic stages run in config order; the first stage with ≥1 exact match
 *   wins and short-circuits — no later stage and no fuzzy pass run.
 * - A stage whose candidate identifier is ABSENT is skipped (fall-through), so a
 *   record with only a BVN still reaches the BVN stage, and one with neither falls
 *   through to fuzzy.
 * - Only when no deterministic stage fires does the weighted fuzzy pass run and
 *   band the result (probable / none, or exact via an optional auto-accept
 *   threshold). Read-only — it never blocks or merges.
 */
class DuplicateCascade
{
    public function __construct(
        private readonly DeterministicMatcher $deterministic,
        private readonly MatchingEngine $engine,
        private readonly FieldNormalizer $normalizer,
    ) {}

    /**
     * @param  array<string, mixed>  $candidate
     * @param  list<array<string, mixed>>  $existing  each may carry an `id`
     * @return array{band: MatchBand, stage: list<string>|null, results: list<MatchResult>}
     */
    public function evaluate(array $candidate, array $existing, MatchingConfig $config): array
    {
        // Ordered deterministic stages — stop at the first that fires.
        foreach ($config->deterministicKeySets() as $keySet) {
            if (! $this->candidateHasKey($candidate, $keySet)) {
                continue; // absent identifier → skip this stage, fall through
            }

            $results = $this->deterministic->matchKeySets($candidate, $existing, [$keySet]);
            if ($results !== []) {
                return ['band' => MatchBand::Exact, 'stage' => $keySet, 'results' => $results];
            }
        }

        // No exact stage fired → the weighted fuzzy pass decides the band.
        $results = array_values(array_filter(
            $this->engine->match($candidate, $existing, $config),
            static fn (MatchResult $r) => $r->band !== MatchBand::None,
        ));

        // engine->match returns most-confident first, so the head carries the band.
        return [
            'band' => $results === [] ? MatchBand::None : $results[0]->band,
            'stage' => null,
            'results' => $results,
        ];
    }

    /**
     * Every field of the key set must be present on the candidate (after
     * normalisation) for the stage to be eligible.
     *
     * @param  array<string, mixed>  $candidate
     * @param  list<string>  $keySet
     */
    private function candidateHasKey(array $candidate, array $keySet): bool
    {
        if ($keySet === []) {
            return false;
        }

        foreach ($keySet as $field) {
            if ($this->normalizer->normalize($field, $candidate[$field] ?? null) === null) {
                return false;
            }
        }

        return true;
    }
}
