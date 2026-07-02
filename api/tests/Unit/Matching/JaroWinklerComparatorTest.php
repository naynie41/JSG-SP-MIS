<?php

declare(strict_types=1);

namespace Tests\Unit\Matching;

use App\Domain\Matching\Scoring\Comparators\JaroWinklerComparator;
use PHPUnit\Framework\TestCase;

class JaroWinklerComparatorTest extends TestCase
{
    private JaroWinklerComparator $jw;

    protected function setUp(): void
    {
        parent::setUp();
        $this->jw = new JaroWinklerComparator;
    }

    public function test_identical_strings_score_one(): void
    {
        $this->assertSame(1.0, $this->jw->compare('sadiq', 'sadiq'));
    }

    public function test_empty_or_missing_scores_zero(): void
    {
        $this->assertSame(0.0, $this->jw->compare(null, 'sadiq'));
        $this->assertSame(0.0, $this->jw->compare('sadiq', ''));
    }

    public function test_known_pairs_match_reference_values(): void
    {
        // Classic Jaro-Winkler reference values.
        $this->assertEqualsWithDelta(0.961, $this->jw->compare('martha', 'marhta'), 0.001);
        $this->assertEqualsWithDelta(0.813, $this->jw->compare('dixon', 'dicksonx'), 0.001);
    }

    public function test_a_one_character_typo_scores_high(): void
    {
        $this->assertGreaterThan(0.9, $this->jw->compare('ibrahim', 'ibraheem'));
    }

    public function test_unrelated_strings_score_low(): void
    {
        $this->assertLessThan(0.5, $this->jw->compare('abc', 'xyz'));
    }
}
