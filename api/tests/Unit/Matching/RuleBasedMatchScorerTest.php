<?php

declare(strict_types=1);

namespace Tests\Unit\Matching;

use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Scoring\Comparators\ComparatorRegistry;
use App\Domain\Matching\Scoring\FieldNormalizer;
use App\Domain\Matching\Scoring\RuleBasedMatchScorer;
use PHPUnit\Framework\TestCase;

class RuleBasedMatchScorerTest extends TestCase
{
    private RuleBasedMatchScorer $scorer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scorer = new RuleBasedMatchScorer(new ComparatorRegistry, new FieldNormalizer);
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

    public function test_a_deterministic_nin_match_is_flagged_even_with_different_names(): void
    {
        $score = $this->scorer->score(
            ['nin' => '22200000011', 'first_name' => 'Amina', 'last_name' => 'Sadiq'],
            ['nin' => '222-000-000-11', 'first_name' => 'Zainab', 'last_name' => 'Umar'],
            $this->config(),
        );

        $this->assertTrue($score->deterministic);
        $ninRule = collect($score->explanation)->firstWhere('fields', ['nin']);
        $this->assertTrue($ninRule['matched']);
    }

    public function test_identical_people_score_near_one(): void
    {
        $record = [
            'phone' => '08030000001', 'date_of_birth' => '1990-01-01',
            'first_name' => 'Amina', 'last_name' => 'Sadiq',
            'lga' => 'dutse', 'ward' => 'Ward 1', 'gender' => 'female',
        ];

        $score = $this->scorer->score($record, $record, $this->config());

        $this->assertFalse($score->deterministic); // no nin/bvn supplied
        $this->assertEqualsWithDelta(1.0, $score->composite, 0.0001);
    }

    public function test_missing_field_contributes_zero_and_is_marked_absent(): void
    {
        $score = $this->scorer->score(
            ['last_name' => 'Sadiq', 'phone' => '08030000001'],
            ['last_name' => 'Sadiq'], // no phone
            $this->config(),
        );

        $phoneRule = collect($score->explanation)->firstWhere('field', 'phone');
        $this->assertFalse($phoneRule['present']);
        $this->assertSame(0.0, $phoneRule['similarity']);
        $this->assertSame(0.0, $phoneRule['contribution']);
    }

    public function test_explanation_never_contains_raw_values(): void
    {
        $flat = json_encode($this->scorer->score(
            ['nin' => '22200000011', 'last_name' => 'Sadiq'],
            ['nin' => '22200000011', 'last_name' => 'Sadiq'],
            $this->config(),
        )->explanation);

        // Only field names / comparators / scores — no identifiers or names.
        $this->assertStringNotContainsString('22200000011', (string) $flat);
        $this->assertStringNotContainsString('Sadiq', (string) $flat);
    }
}
