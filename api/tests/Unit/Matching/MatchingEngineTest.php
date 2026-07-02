<?php

declare(strict_types=1);

namespace Tests\Unit\Matching;

use App\Domain\Matching\Engine\MatchingEngine;
use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Scoring\Comparators\ComparatorRegistry;
use App\Domain\Matching\Scoring\FieldNormalizer;
use App\Domain\Matching\Scoring\RuleBasedMatchScorer;
use PHPUnit\Framework\TestCase;

class MatchingEngineTest extends TestCase
{
    private MatchingEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = new MatchingEngine(new RuleBasedMatchScorer(new ComparatorRegistry, new FieldNormalizer));
    }

    private function config(): MatchingConfig
    {
        return new MatchingConfig([
            'deterministic_rules' => [['nin'], ['bvn']],
            'fuzzy_fields' => [
                ['field' => 'phone', 'comparator' => 'exact', 'weight' => 0.25],
                ['field' => 'date_of_birth', 'comparator' => 'exact', 'weight' => 0.25],
                ['field' => 'last_name', 'comparator' => 'jaro_winkler', 'weight' => 0.20],
                ['field' => 'first_name', 'comparator' => 'jaro_winkler', 'weight' => 0.15],
                ['field' => 'lga', 'comparator' => 'exact', 'weight' => 0.05],
                ['field' => 'ward', 'comparator' => 'exact', 'weight' => 0.05],
                ['field' => 'gender', 'comparator' => 'exact', 'weight' => 0.05],
            ],
            'review_threshold' => 0.75,
            'auto_accept_threshold' => 0.92,
            'exact_match_behaviour' => 'confirm',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function candidate(): array
    {
        return [
            'nin' => '22200000011',
            'phone' => '08030000001',
            'date_of_birth' => '1990-01-01',
            'first_name' => 'Amina',
            'last_name' => 'Sadiq',
            'gender' => 'female',
            'lga' => 'dutse',
            'ward' => 'Ward 1',
        ];
    }

    public function test_engine_bands_and_ranks_a_mixed_candidate_set(): void
    {
        $existing = [
            // Same NIN, different everything → deterministic → exact.
            ['id' => 'exact-1', 'nin' => '22200000011', 'first_name' => 'Zed', 'last_name' => 'Other'],
            // Different NIN but phone+dob+names match, locale/gender differ → 0.85 → probable.
            ['id' => 'probable-1', 'nin' => '99999999999', 'phone' => '08030000001', 'date_of_birth' => '1990-01-01',
                'first_name' => 'Amina', 'last_name' => 'Sadiq', 'gender' => 'male', 'lga' => 'gumel', 'ward' => 'Ward 9'],
            // Nothing in common → none.
            ['id' => 'none-1', 'nin' => '10101010101', 'phone' => '08079999999', 'date_of_birth' => '1975-12-12',
                'first_name' => 'Bello', 'last_name' => 'Yaro', 'gender' => 'male', 'lga' => 'hadejia', 'ward' => 'Ward 3'],
        ];

        $results = $this->engine->match($this->candidate(), $existing, $this->config());

        // Ranked most-confident first: exact, probable, none.
        $this->assertSame(['exact-1', 'probable-1', 'none-1'], array_map(fn ($r) => $r->reference, $results));
        $this->assertSame(MatchBand::Exact, $results[0]->band);
        $this->assertSame(MatchBand::Probable, $results[1]->band);
        $this->assertSame(MatchBand::None, $results[2]->band);

        // The probable one scores in the review window.
        $this->assertEqualsWithDelta(0.85, $results[1]->score->composite, 0.0001);

        // Explanation is present and per-rule (deterministic + fuzzy entries).
        $types = array_column($results[0]->score->explanation, 'type');
        $this->assertContains('deterministic', $types);
        $this->assertContains('fuzzy', $types);
    }

    public function test_high_fuzzy_score_reaches_exact_via_auto_accept_without_a_deterministic_key(): void
    {
        // Identical demographics but no NIN/BVN → composite 1.0 ≥ 0.92 → exact.
        $existing = [['id' => 'twin'] + array_diff_key($this->candidate(), ['nin' => true])];

        $results = $this->engine->match(
            array_diff_key($this->candidate(), ['nin' => true]),
            $existing,
            $this->config(),
        );

        $this->assertSame(MatchBand::Exact, $results[0]->band);
        $this->assertFalse($results[0]->score->deterministic);
    }
}
