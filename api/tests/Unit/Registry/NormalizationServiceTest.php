<?php

declare(strict_types=1);

namespace Tests\Unit\Registry;

use App\Domain\Registry\Support\NormalizationService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Normalization is pure, so it is tested as a unit — no database, no framework.
 *
 * These cases are the messy reality of MDA files. Each pair below is the SAME person
 * written two ways; if normalization does not collapse them, the duplicate cascade never
 * gets the chance to notice, and the registry quietly holds two records for one citizen.
 */
class NormalizationServiceTest extends TestCase
{
    private NormalizationService $normalizer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->normalizer = new NormalizationService;
    }

    /* --------------------------------------------------------------------- phone */

    /** @return list<array{0: string, 1: string}> */
    public static function phoneCases(): array
    {
        return [
            'national form' => ['08031234567', '08031234567'],
            'e164' => ['+2348031234567', '08031234567'],
            'country code, no plus' => ['2348031234567', '08031234567'],
            'international access prefix' => ['002348031234567', '08031234567'],
            'country code keeping the trunk zero' => ['+234 (0) 803 123 4567', '08031234567'],
            'spaces' => ['0803 123 4567', '08031234567'],
            'dashes' => ['0803-123-4567', '08031234567'],
            'no trunk zero' => ['8031234567', '08031234567'],
            'mixed punctuation' => ['+234-803-123 4567', '08031234567'],
        ];
    }

    #[DataProvider('phoneCases')]
    public function test_every_written_form_of_one_number_normalizes_alike(string $input, string $expected): void
    {
        $this->assertSame($expected, $this->normalizer->phone($input));
    }

    public function test_the_two_forms_in_the_acceptance_criteria_compare_equal(): void
    {
        $this->assertSame(
            $this->normalizer->phone('08031234567'),
            $this->normalizer->phone('+2348031234567'),
        );
    }

    public function test_an_unrecognised_number_is_not_forced_into_the_nigerian_pattern(): void
    {
        // Guessing here would be worse than not normalizing: coercing a foreign or
        // truncated number into 0XXXXXXXXXX could merge two different people.
        $this->assertSame('447700900123', $this->normalizer->phone('+44 7700 900123'));
        $this->assertSame('12345', $this->normalizer->phone('12345'));
    }

    public function test_a_phone_with_no_digits_is_absent_not_empty(): void
    {
        $this->assertNull($this->normalizer->phone('n/a'));
        $this->assertNull($this->normalizer->phone('   '));
        $this->assertNull($this->normalizer->phone(null));
    }

    /* ---------------------------------------------------------------------- name */

    /** @return list<array{0: string, 1: string}> */
    public static function nameCases(): array
    {
        return [
            'double space' => ['MOHAMMED  MUSA', 'mohammed musa'],
            'title case' => ['Mohammed Musa', 'mohammed musa'],
            'leading/trailing space' => ['  Mohammed Musa  ', 'mohammed musa'],
            'tabs and newlines' => ["Mohammed\tMusa", 'mohammed musa'],
            'already lower' => ['mohammed musa', 'mohammed musa'],
        ];
    }

    #[DataProvider('nameCases')]
    public function test_name_spacing_and_case_do_not_change_the_comparison(string $input, string $expected): void
    {
        $this->assertSame($expected, $this->normalizer->name($input));
    }

    public function test_the_two_names_in_the_acceptance_criteria_compare_equal(): void
    {
        $this->assertSame(
            $this->normalizer->name('MOHAMMED  MUSA'),
            $this->normalizer->name('Mohammed Musa'),
        );
    }

    public function test_punctuation_inside_a_name_is_preserved(): void
    {
        // Whether O'Brien and OBrien are the same name is a judgement for the fuzzy
        // comparator; erasing the apostrophe here would make that decision silently.
        $this->assertSame("o'brien", $this->normalizer->name("O'Brien"));
        $this->assertSame('al-amin', $this->normalizer->name('Al-Amin'));
    }

    public function test_token_order_folding_is_opt_in(): void
    {
        // The default keeps order — surname-first is not the same record shape as
        // surname-last, and folding it away everywhere would over-match.
        $this->assertNotSame(
            $this->normalizer->name('Musa Mohammed'),
            $this->normalizer->name('Mohammed Musa'),
        );

        // Opting in collapses them, for a full-name column of unknown order.
        $this->assertSame(
            $this->normalizer->nameTokensSorted('Musa Mohammed'),
            $this->normalizer->nameTokensSorted('Mohammed Musa'),
        );
        $this->assertSame('mohammed musa', $this->normalizer->nameTokensSorted('MUSA   Mohammed'));
    }

    /* ---------------------------------------------------------------------- date */

    /** @return list<array{0: string, 1: ?string}> */
    public static function dateCases(): array
    {
        return [
            'iso' => ['1995-03-12', '1995-03-12'],
            'day-first slashes' => ['12/03/1995', '1995-03-12'],
            'day-first dashes' => ['12-03-1995', '1995-03-12'],
            'day-first dots' => ['12.03.1995', '1995-03-12'],
            'unpadded day-first' => ['2/3/1995', '1995-03-02'],
            'iso slashes' => ['1995/03/12', '1995-03-12'],
            'short month name' => ['12 Mar 1995', '1995-03-12'],
            'full month name' => ['12 March 1995', '1995-03-12'],
            'iso datetime' => ['1995-03-12T00:00:00Z', '1995-03-12'],
        ];
    }

    #[DataProvider('dateCases')]
    public function test_common_written_dates_parse_to_iso(string $input, ?string $expected): void
    {
        $this->assertSame($expected, $this->normalizer->date($input));
    }

    public function test_the_two_dates_in_the_acceptance_criteria_compare_equal(): void
    {
        $this->assertSame(
            $this->normalizer->date('12/03/1995'),
            $this->normalizer->date('1995-03-12'),
        );
    }

    public function test_ambiguous_slash_dates_are_read_day_first_not_month_first(): void
    {
        // PHP's own strtotime() reads 12/03/1995 as 3 DECEMBER. Nigerian forms are
        // written day-first, and a birth date read nine months wrong also shifts
        // block_name_dob — which decides who the fuzzy matcher ever compares.
        $this->assertSame('1995-12-03', date('Y-m-d', (int) strtotime('12/03/1995')));
        $this->assertSame('1995-03-12', $this->normalizer->date('12/03/1995'));
    }

    public function test_an_impossible_date_is_rejected_rather_than_rolled_over(): void
    {
        // 31 February must not quietly become 3 March.
        $this->assertNull($this->normalizer->date('31/02/1995'));
        $this->assertNull($this->normalizer->date('45/13/1995'));
    }

    public function test_unparseable_input_returns_null_rather_than_a_guess(): void
    {
        $this->assertNull($this->normalizer->date('not a date'));
        $this->assertNull($this->normalizer->date(''));
        $this->assertNull($this->normalizer->date(null));
    }

    /* ----------------------------------------------------------------- NIN / BVN */

    /** @return list<array{0: string, 1: ?string}> */
    public static function identifierCases(): array
    {
        return [
            'plain' => ['22200000011', '22200000011'],
            'spaces' => ['222 000 000 11', '22200000011'],
            'hyphens' => ['222-000-000-11', '22200000011'],
            'mixed' => [' 222-000 000-11 ', '22200000011'],
            'no digits' => ['not-a-nin', null],
            'blank' => ['   ', null],
        ];
    }

    #[DataProvider('identifierCases')]
    public function test_identifiers_reduce_to_their_digits(string $input, ?string $expected): void
    {
        $this->assertSame($expected, $this->normalizer->identifier($input));
    }

    public function test_a_non_numeric_identifier_normalizes_to_absent(): void
    {
        // Documented boundary (FR-REG-05): this is ABSENT, not malformed, so it does not
        // reject the row — an absent optional NIN is valid.
        $this->assertNull($this->normalizer->identifier('not-a-nin'));
    }

    /* ------------------------------------------------------------------- enum-ish */

    public function test_enum_keys_fold_case_spaces_and_hyphens(): void
    {
        foreach (['Birnin Kudu', 'birnin-kudu', 'BIRNIN_KUDU', ' Birnin  Kudu '] as $written) {
            $this->assertSame('birnin_kudu', $this->normalizer->enumKey($written));
        }

        $this->assertSame('female', $this->normalizer->enumKey('Female'));
    }

    public function test_source_truthiness_for_the_household_head_flag(): void
    {
        foreach (['1', 'true', 'YES', 'y', 'Head'] as $truthy) {
            $this->assertTrue($this->normalizer->boolean($truthy));
        }
        foreach (['0', 'false', 'no', '', 'maybe', null] as $falsy) {
            $this->assertFalse($this->normalizer->boolean($falsy));
        }
    }

    /* -------------------------------------------------------------- determinism */

    public function test_normalization_is_deterministic(): void
    {
        // The cascade's verdicts are only reproducible if this is. Same input, same
        // output, every time and in any order.
        $inputs = ['+234 (0) 803 123 4567', 'MOHAMMED  MUSA', '12/03/1995', '222-000-000-11'];

        foreach ($inputs as $input) {
            $first = [
                $this->normalizer->phone($input),
                $this->normalizer->name($input),
                $this->normalizer->date($input),
                $this->normalizer->identifier($input),
            ];

            for ($i = 0; $i < 5; $i++) {
                $this->assertSame($first, [
                    $this->normalizer->phone($input),
                    $this->normalizer->name($input),
                    $this->normalizer->date($input),
                    $this->normalizer->identifier($input),
                ]);
            }
        }
    }

    /* ------------------------------------------------------- dispatch by field */

    public function test_for_field_picks_the_rule_from_the_canonical_schema(): void
    {
        $this->assertSame('08031234567', $this->normalizer->forField('phone', '+234 803 123 4567'));
        $this->assertSame('22200000011', $this->normalizer->forField('nin', '222-000-000-11'));
        $this->assertSame('1995-03-12', $this->normalizer->forField('date_of_birth', '12/03/1995'));
        $this->assertSame('birnin_kudu', $this->normalizer->forField('lga', 'Birnin Kudu'));
        $this->assertSame('mohammed musa', $this->normalizer->forField('first_name', 'MOHAMMED  MUSA'));
    }
}
