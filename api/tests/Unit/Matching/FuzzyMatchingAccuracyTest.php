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

/**
 * Scoring accuracy on a small labelled fixture set (PRD FR-DUP-03): spelling
 * variants, transposed names, and DOB typos must land in the duplicate bands
 * (probable/exact); clear non-duplicates must not. Reports precision/recall.
 */
class FuzzyMatchingAccuracyTest extends TestCase
{
    private MatchingEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engine = new MatchingEngine(new RuleBasedMatchScorer(new ComparatorRegistry, new FieldNormalizer));
    }

    /** The seeded DEFAULT rule set, in memory. */
    private function config(): MatchingConfig
    {
        return new MatchingConfig([
            'deterministic_rules' => [['nin'], ['bvn']],
            'fuzzy_fields' => [
                ['field' => 'last_name', 'comparator' => 'phonetic', 'weight' => 0.25],
                ['field' => 'first_name', 'comparator' => 'phonetic', 'weight' => 0.15],
                ['field' => 'date_of_birth', 'comparator' => 'date_proximity', 'weight' => 0.20],
                ['field' => 'phone', 'comparator' => 'exact', 'weight' => 0.20],
                ['field' => 'lga', 'comparator' => 'exact', 'weight' => 0.05],
                ['field' => 'ward', 'comparator' => 'exact', 'weight' => 0.05],
                ['field' => 'address', 'comparator' => 'jaro_winkler', 'weight' => 0.10],
            ],
            'review_threshold' => 0.75,
            'auto_accept_threshold' => 0.92,
            'exact_match_behaviour' => 'confirm',
        ]);
    }

    public function test_precision_and_recall_on_labelled_fixtures(): void
    {
        $config = $this->config();
        $tp = $fp = $fn = $tn = 0;

        foreach ($this->fixtures() as $label => $fixture) {
            [$candidate, $existing, $isDuplicate] = $fixture;
            $result = $this->engine->match($candidate, [$existing], $config)[0];
            $predictedDuplicate = $result->band !== MatchBand::None;

            if ($isDuplicate && $predictedDuplicate) {
                $tp++;
            } elseif (! $isDuplicate && $predictedDuplicate) {
                $fp++;
            } elseif ($isDuplicate) {
                $fn++;
            } else {
                $tn++;
            }

            // True duplicates should be probable/exact; non-duplicates none.
            $this->assertSame(
                $isDuplicate,
                $predictedDuplicate,
                "Fixture {$label} mis-classified (band={$result->band->value}, score={$result->score->composite})",
            );
        }

        $precision = (float) $tp / max(1, $tp + $fp);
        $recall = (float) $tp / max(1, $tp + $fn);

        fwrite(STDERR, sprintf(
            "\n[fuzzy fixtures] precision=%.2f recall=%.2f (tp=%d fp=%d fn=%d tn=%d)\n",
            $precision, $recall, $tp, $fp, $fn, $tn,
        ));

        $this->assertSame(1.0, $recall, 'All labelled duplicates should be caught');
        $this->assertSame(1.0, $precision, 'No non-duplicate should be flagged');
    }

    /**
     * @return array<string, array{array<string, mixed>, array<string, mixed>, bool}>
     */
    private function fixtures(): array
    {
        return [
            'DUP spelling variant' => [
                ['last_name' => 'Sani', 'first_name' => 'Muhammadu', 'date_of_birth' => '1990-05-10', 'phone' => '08031234567', 'lga' => 'dutse', 'ward' => 'Ward 3'],
                ['id' => 'e1', 'last_name' => 'Saani', 'first_name' => 'Mohammed', 'date_of_birth' => '1990-05-10', 'phone' => '08031234567', 'lga' => 'dutse', 'ward' => 'Ward 9', 'address' => '5 Market Rd'],
                true,
            ],
            'DUP transposed name' => [
                ['last_name' => 'Sadiq', 'first_name' => 'Amina', 'date_of_birth' => '1988-02-20', 'phone' => '08030000002', 'lga' => 'gumel', 'ward' => 'Ward 1'],
                ['id' => 'e2', 'last_name' => 'Sadqi', 'first_name' => 'Amina', 'date_of_birth' => '1988-02-20', 'phone' => '08030000002', 'lga' => 'gumel', 'ward' => 'Ward 4'],
                true,
            ],
            'DUP dob typo' => [
                ['last_name' => 'Bello', 'first_name' => 'Aisha', 'date_of_birth' => '1985-06-15', 'phone' => '08030000003', 'lga' => 'kazaure', 'ward' => 'Ward 2'],
                ['id' => 'e3', 'last_name' => 'Bello', 'first_name' => 'Aishatu', 'date_of_birth' => '1985-06-18', 'phone' => '08030000003', 'lga' => 'kazaure', 'ward' => 'Ward 7'],
                true,
            ],
            'DUP near-perfect' => [
                ['last_name' => 'Yusuf', 'first_name' => 'Ibrahim', 'date_of_birth' => '1979-11-30', 'phone' => '08030000004', 'lga' => 'hadejia', 'ward' => 'Ward 5', 'address' => '10 Unity Road'],
                ['id' => 'e4', 'last_name' => 'Yusuf', 'first_name' => 'Ibrahim', 'date_of_birth' => '1979-11-30', 'phone' => '08030000004', 'lga' => 'hadejia', 'ward' => 'Ward 5', 'address' => '10 Unity Road'],
                true,
            ],
            'NOT clear non-duplicate' => [
                ['last_name' => 'Adamu', 'first_name' => 'Grace', 'date_of_birth' => '2000-01-01', 'phone' => '08039999999', 'lga' => 'ringim', 'ward' => 'Ward 1'],
                ['id' => 'n1', 'last_name' => 'Okoro', 'first_name' => 'Chidi', 'date_of_birth' => '1970-07-07', 'phone' => '08031111111', 'lga' => 'jahun', 'ward' => 'Ward 9'],
                false,
            ],
            'NOT same surname and dob only' => [
                ['last_name' => 'Musa', 'first_name' => 'Amina', 'date_of_birth' => '1995-03-03', 'lga' => 'dutse', 'ward' => 'Ward 1'],
                ['id' => 'n2', 'last_name' => 'Musa', 'first_name' => 'Yusuf', 'date_of_birth' => '1995-03-03', 'lga' => 'gumel', 'ward' => 'Ward 8'],
                false,
            ],
            'NOT same first name only' => [
                ['last_name' => 'Ali', 'first_name' => 'Fatima', 'date_of_birth' => '1992-09-09'],
                ['id' => 'n3', 'last_name' => 'Bala', 'first_name' => 'Fatima', 'date_of_birth' => '1970-01-01'],
                false,
            ],
        ];
    }
}
