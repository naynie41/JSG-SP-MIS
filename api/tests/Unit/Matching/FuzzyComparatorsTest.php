<?php

declare(strict_types=1);

namespace Tests\Unit\Matching;

use App\Domain\Matching\Scoring\Comparators\DateProximityComparator;
use App\Domain\Matching\Scoring\Comparators\PhoneticComparator;
use PHPUnit\Framework\TestCase;

class FuzzyComparatorsTest extends TestCase
{
    public function test_phonetic_comparator_scores_name_variants_as_one(): void
    {
        $phonetic = new PhoneticComparator;

        $this->assertSame(1.0, $phonetic->compare('muhammad', 'mohammed'));
        $this->assertSame(1.0, $phonetic->compare('sadiq', 'sadqi'));
        $this->assertSame(0.0, $phonetic->compare('sani', null));
    }

    public function test_phonetic_comparator_falls_back_to_edit_distance_for_non_variants(): void
    {
        $phonetic = new PhoneticComparator;

        // Different phonetic codes → not a variant → Jaro-Winkler fallback (< 1.0).
        $this->assertLessThan(1.0, $phonetic->compare('sani', 'bello'));
        // A near-miss that is not a phonetic variant still gets partial credit.
        $partial = $phonetic->compare('sanusi', 'sani');
        $this->assertGreaterThan(0.0, $partial);
        $this->assertLessThan(1.0, $partial);
    }

    public function test_date_proximity_is_full_for_equal_and_decays_with_distance(): void
    {
        $proximity = new DateProximityComparator;

        $this->assertSame(1.0, $proximity->compare('1990-05-10', '1990-05-10'));
        // A few days apart (typo) still scores very high.
        $this->assertGreaterThan(0.9, $proximity->compare('1990-05-10', '1990-05-13'));
        // Years apart decays toward zero.
        $this->assertLessThan(0.4, $proximity->compare('1990-05-10', '1993-01-01'));
        $this->assertSame(0.0, $proximity->compare('1990-05-10', '1980-01-01'));
    }
}
