<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Matching\Enums\ExactMatchBehaviour;
use App\Domain\Matching\Enums\MatchComparator;
use App\Domain\Matching\Models\MatchingConfig;
use Illuminate\Database\Seeder;

/**
 * Seeds the DEFAULT duplicate-matching configuration (PRD FR-DUP-02/03, §9) — real
 * production config, not sample data. Idempotent: no-op once a config exists.
 *
 * The default encodes the ORDERED CASCADE (§9): exact NIN → exact BVN → fuzzy
 * name/phone. The `deterministic_rules` ORDER is significant — the cascade
 * evaluates the key sets top-to-bottom and STOPS at the first exact match; a stage
 * whose identifier is absent is skipped (fall-through). Everything here is
 * admin-editable and versioned — no cascade numbers are hard-coded in code.
 *
 * DEFAULT (documented in app/Domain/Matching/README.md):
 *  - Deterministic decisive key sets, IN ORDER: [nin] then [bvn] → exact band.
 *  - Fuzzy weights (sum 1.00): last_name .25 (phonetic), first_name .15
 *    (phonetic), date_of_birth .20 (date_proximity), phone .20 (exact),
 *    lga .05, ward .05, address .10 (jaro_winkler).
 *  - review_threshold 0.75, auto_accept_threshold 0.92.
 *  - exact_match_behaviour: confirm (human confirms exact matches).
 */
class MatchingConfigSeeder extends Seeder
{
    public function run(): void
    {
        if (MatchingConfig::query()->exists()) {
            return;
        }

        MatchingConfig::create([
            'version' => 1,
            'is_active' => true,
            'deterministic_rules' => [['nin'], ['bvn']],
            'fuzzy_fields' => [
                ['field' => 'last_name', 'comparator' => MatchComparator::Phonetic->value, 'weight' => 0.25],
                ['field' => 'first_name', 'comparator' => MatchComparator::Phonetic->value, 'weight' => 0.15],
                ['field' => 'date_of_birth', 'comparator' => MatchComparator::DateProximity->value, 'weight' => 0.20],
                ['field' => 'phone', 'comparator' => MatchComparator::Exact->value, 'weight' => 0.20],
                ['field' => 'lga', 'comparator' => MatchComparator::Exact->value, 'weight' => 0.05],
                ['field' => 'ward', 'comparator' => MatchComparator::Exact->value, 'weight' => 0.05],
                ['field' => 'address', 'comparator' => MatchComparator::JaroWinkler->value, 'weight' => 0.10],
            ],
            'review_threshold' => 0.75,
            'auto_accept_threshold' => 0.92,
            'exact_match_behaviour' => ExactMatchBehaviour::Confirm->value,
            'description' => 'Default rule set (Phase 3).',
        ]);
    }
}
