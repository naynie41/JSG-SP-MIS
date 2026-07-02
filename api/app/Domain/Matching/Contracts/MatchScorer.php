<?php

declare(strict_types=1);

namespace App\Domain\Matching\Contracts;

use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Scoring\MatchScore;
use App\Domain\Matching\Scoring\RuleBasedMatchScorer;

/**
 * Scores one candidate record against one existing record under a matching
 * configuration (PRD FR-DUP-03). This is the SINGLE seam an alternative scorer
 * implements — including a future external/AI scorer (FR-DUP-07) — so callers
 * (the MatchingEngine, the pre-save duplicate check, the standalone lookup) never
 * change when the scoring strategy changes.
 *
 * ## Contract
 * - Input records are associative arrays of canonical beneficiary fields
 *   (`nin`, `bvn`, `phone`, `first_name`, `last_name`, `date_of_birth`, `gender`,
 *   `lga`, `ward`, …). Values may be null/absent; implementations MUST treat a
 *   missing value on either side as "no evidence" (never a positive match).
 * - The returned {@see MatchScore} carries: a normalised composite in `[0,1]`, a
 *   `deterministic` flag (a decisive exact key set matched), and a structured,
 *   PII-free `explanation` of which rules fired (field NAMES + similarities only,
 *   never the raw values) — for transparency and audit.
 * - Banding (exact/probable/none) is applied by the engine from the config
 *   thresholds, NOT by the scorer. A scorer only measures similarity.
 * - Implementations MUST be pure (no side effects) and deterministic for a given
 *   input, so results are reproducible and auditable.
 *
 * The built-in {@see RuleBasedMatchScorer} provides the deterministic + fuzzy
 * scorer. To add an AI scorer later, implement this interface and bind it in the
 * MatchingServiceProvider — no caller edits needed.
 */
interface MatchScorer
{
    /**
     * @param  array<string, mixed>  $candidate  the record being checked
     * @param  array<string, mixed>  $existing  an existing registry record
     */
    public function score(array $candidate, array $existing, MatchingConfig $config): MatchScore;
}
