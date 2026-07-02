<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Matching\Engine\MatchingEngine;
use App\Domain\Matching\Engine\MatchResult;
use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Services\MatchingConfigService;

/**
 * Fuzzy duplicate check against the registry (PRD FR-DUP-03): blocks to a small
 * candidate set, scores each with the configured weighted comparators, and bands
 * the results. Returns only the interesting matches (exact + probable), ranked.
 *
 * Read-only: it surfaces matches, it does not block. Runs inline for single
 * lookups (bounded candidate set keeps it within NFR-PERF-01's 5s) and from the
 * queue worker for batches.
 */
class FuzzyDuplicateFinder
{
    public function __construct(
        private readonly CandidateGatherer $gatherer,
        private readonly MatchingEngine $engine,
        private readonly MatchingConfigService $configs,
    ) {}

    /**
     * @param  array<string, mixed>  $candidate
     * @return list<MatchResult> exact + probable matches, most-confident first
     */
    public function find(array $candidate, ?MatchingConfig $config = null, ?string $excludeId = null): array
    {
        $config ??= $this->configs->active();

        $candidates = $this->gatherer->gather($candidate, $config, $excludeId);
        $results = $this->engine->match($candidate, $candidates, $config);

        return array_values(array_filter($results, static fn (MatchResult $r) => $r->band !== MatchBand::None));
    }
}
