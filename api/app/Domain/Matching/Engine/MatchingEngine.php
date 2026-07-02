<?php

declare(strict_types=1);

namespace App\Domain\Matching\Engine;

use App\Domain\Matching\Contracts\MatchScorer;
use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Scoring\MatchScore;

/**
 * Runs a candidate record against a set of existing records under a matching
 * configuration and returns a banded {@see MatchResult} per comparison
 * (PRD FR-DUP-03). Pure orchestration: scoring is delegated to the pluggable
 * {@see MatchScorer}; banding applies the config thresholds. Candidate selection
 * (blocking) is the caller's responsibility — this engine only scores + bands.
 */
class MatchingEngine
{
    public function __construct(private readonly MatchScorer $scorer) {}

    /**
     * @param  array<string, mixed>  $candidate
     * @param  iterable<array<string, mixed>>  $existingRecords  each may carry an `id`
     * @return list<MatchResult> ranked most-confident first
     */
    public function match(array $candidate, iterable $existingRecords, MatchingConfig $config): array
    {
        $results = [];
        foreach ($existingRecords as $existing) {
            $reference = isset($existing['id']) ? (string) $existing['id'] : null;
            $score = $this->scorer->score($candidate, $existing, $config);
            $results[] = new MatchResult($reference, $this->band($score, $config), $score);
        }

        usort($results, static function (MatchResult $a, MatchResult $b): int {
            return [$b->band->rank(), $b->score->composite] <=> [$a->band->rank(), $a->score->composite];
        });

        return $results;
    }

    private function band(MatchScore $score, MatchingConfig $config): MatchBand
    {
        if ($score->deterministic) {
            return MatchBand::Exact;
        }

        $auto = $config->auto_accept_threshold;
        if ($auto !== null && $score->composite >= $auto) {
            return MatchBand::Exact;
        }

        if ($score->composite >= $config->review_threshold) {
            return MatchBand::Probable;
        }

        return MatchBand::None;
    }
}
