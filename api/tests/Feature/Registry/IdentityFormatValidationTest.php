<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Reference\Models\Lga as LgaModel;
use App\Domain\Reference\Models\Ward as WardModel;
use App\Domain\Reference\Services\ReferenceDataCache;
use App\Domain\Registry\Imports\ImportRowValidator;
use App\Domain\Registry\Support\BeneficiaryRules;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Concrete identity-field formats in the validation step (FR-REG-05, sharpened).
 *
 * The rule this enforces: a PRESENT-but-malformed identity value rejects the WHOLE row.
 * That is not fussiness — NIN, BVN, name and phone are what the duplicate cascade
 * compares people on, so a malformed one does not fail loudly. It produces a confident
 * wrong answer: two strangers declared the same person, or one person split in two.
 *
 * A malformed NON-identity value drops that field and keeps the row (FR-REG-09), because
 * a bad ward is a gap in a record, not a claim about who someone is.
 *
 * Validation runs on NORMALIZED values, so `+234 803…` and `0803…` are judged as one
 * number. The messages DESCRIBE what is wrong ("has 9 digits", "contains characters that
 * are not digits") without ever printing the value: identity fields are PII, and this
 * text lands in the row error report and the sync run log. The row number locates it.
 */
class IdentityFormatValidationTest extends TestCase
{
    use RefreshDatabase;

    /** A row that passes everything, so each case below changes exactly one thing. */
    private function goodRow(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Ada',
            'last_name' => 'Okoye',
            'nin' => '22200000011',
            'bvn' => '33300000022',
            'phone' => '08031234567',
            'date_of_birth' => '12/03/1995',
            'gender' => 'female',
            'lga' => 'dutse',
            'ward' => 'Limawa',
        ], $overrides);
    }

    private function validate(array $overrides = []): array
    {
        return app(ImportRowValidator::class)->validate($this->goodRow($overrides));
    }

    /** @return list<string> */
    private function identityMessages(array $result): array
    {
        return array_map(fn (array $e): string => $e['message'], $result['identity_errors']);
    }

    public function test_a_clean_row_passes(): void
    {
        $result = $this->validate();

        $this->assertSame([], $result['identity_errors']);
        $this->assertSame([], $result['dropped_fields']);
    }

    /* ------------------------------------------------------------- NIN / BVN */

    /** @return list<array{0: string, 1: string, 2: string}> */
    public static function malformedIdentifiers(): array
    {
        return [
            'too short' => ['nin', '222000000', 'has 9'],
            'too long' => ['nin', '2220000001199', 'has 13'],
            'non-numeric' => ['nin', '2220000ABCD', 'not digits'],
            'bvn too short' => ['bvn', '333', 'has 3'],
            'bvn too long' => ['bvn', '333000000221234', 'has 15'],
            'bvn non-numeric' => ['bvn', 'BVN33300000', 'not digits'],
        ];
    }

    #[DataProvider('malformedIdentifiers')]
    public function test_a_malformed_identifier_rejects_the_whole_row(string $field, string $value, string $expect): void
    {
        $result = $this->validate([$field => $value]);

        $messages = $this->identityMessages($result);
        $this->assertNotSame([], $messages, "{$field} = {$value} must reject the row");

        $joined = implode(' | ', $messages);
        $this->assertStringContainsString(strtoupper($field), $joined);
        // The report says WHY, not just that something is wrong.
        $this->assertMatchesRegularExpression('/'.preg_quote($expect, '/').'|digits/i', $joined);

        // ...and it DESCRIBES the bad value without printing it. A NIN/BVN is PII
        // (CLAUDE.md §8) and this text reaches the row error report and the sync run
        // log, both read across MDAs. The row number is the locator, not the value.
        $this->assertStringNotContainsString($value, $joined);
    }

    public function test_an_error_message_never_prints_the_identifier_it_rejected(): void
    {
        // Guarded explicitly, because the natural way to write "got 9 digits" is to
        // quote the number — and that would put a failed identifier into a log in clear.
        foreach (['nin' => '9999999', 'bvn' => '8888888', 'phone' => '070123'] as $field => $value) {
            $messages = implode(' ', $this->identityMessages($this->validate([$field => $value])));

            $this->assertNotSame('', $messages, "{$field} must still be reported");
            $this->assertStringNotContainsString($value, $messages);
        }
    }

    public function test_a_correctly_formatted_identifier_with_punctuation_is_accepted(): void
    {
        // Normalization strips punctuation before the length is judged — the officer's
        // spacing is not a data error.
        $result = $this->validate(['nin' => '222-0000-0011']);

        $this->assertSame([], $result['identity_errors']);
        $this->assertSame('22200000011', $result['payload']['nin']);
    }

    public function test_an_absent_optional_identifier_is_still_valid(): void
    {
        // FR-REG-05 is explicit: absent is fine, malformed is not.
        $result = $this->validate(['nin' => '', 'bvn' => '']);

        $this->assertSame([], $result['identity_errors']);
        $this->assertNull($result['payload']['nin']);
    }

    /* ---------------------------------------------------------------- phone */

    /** @return list<array{0: string}> */
    public static function malformedPhones(): array
    {
        return [
            'far too short' => ['12'],
            'letters' => ['not a phone'],
            'wrong length' => ['0803123456789'],
        ];
    }

    #[DataProvider('malformedPhones')]
    public function test_a_malformed_phone_rejects_the_row(string $value): void
    {
        // Phone is an IDENTITY field: it feeds the fuzzy stage, so junk in it changes who
        // a row is compared against rather than merely being untidy.
        $result = $this->validate(['phone' => $value]);

        $messages = $this->identityMessages($result);
        $this->assertNotSame([], $messages, "phone = {$value} must reject the row");
        $this->assertStringContainsStringIgnoringCase('phone', implode(' ', $messages));
    }

    /** @return list<array{0: string}> */
    public static function validPhoneSpellings(): array
    {
        return [['08031234567'], ['+234 803 123 4567'], ['00234 803 123 4567'], ['8031234567'], ['0803 123 4567']];
    }

    #[DataProvider('validPhoneSpellings')]
    public function test_every_written_form_of_one_number_is_accepted(string $value): void
    {
        // Validity is judged on the NORMALIZED value, so the many ways people write one
        // number are one number.
        $result = $this->validate(['phone' => $value]);

        $this->assertSame([], $result['identity_errors'], "“{$value}” is the same number as 08031234567");
        // ...and the ORIGINAL spelling is what gets stored (FR-REG-16).
        $this->assertSame($value, $result['payload']['phone']);
    }

    /* ------------------------------------------------- date of birth (non-identity) */

    public function test_an_unparseable_date_drops_the_field_and_keeps_the_row(): void
    {
        // Non-identity (FR-REG-09): a bad date is a gap, not a claim about identity.
        $result = $this->validate(['date_of_birth' => 'not a date']);

        $this->assertSame([], $result['identity_errors'], 'a bad date must not reject the row');
        $this->assertNotSame([], $result['dropped_fields']);
        $this->assertNull($result['payload']['date_of_birth']);
        $this->assertStringContainsStringIgnoringCase(
            'date of birth',
            $result['dropped_fields'][0]['message'],
        );
    }

    public function test_a_future_date_of_birth_is_refused(): void
    {
        $result = $this->validate(['date_of_birth' => now()->addYear()->format('d/m/Y')]);

        $this->assertNotSame([], $result['dropped_fields']);
        $this->assertStringContainsStringIgnoringCase('future', $result['dropped_fields'][0]['message']);
        $this->assertNull($result['payload']['date_of_birth']);
    }

    public function test_an_implausibly_early_date_of_birth_is_refused(): void
    {
        $result = $this->validate(['date_of_birth' => '01/01/1850']);

        $this->assertNotSame([], $result['dropped_fields']);
        $this->assertNull($result['payload']['date_of_birth']);
    }

    public function test_a_day_first_date_is_read_day_first(): void
    {
        // `12/03/1995` is 12 March, not 3 December. Nine months of drift moves
        // `block_name_dob`, the key deciding which candidates the matcher ever sees.
        $result = $this->validate(['date_of_birth' => '12/03/1995']);

        $this->assertSame('1995-03-12', $result['payload']['date_of_birth']);
    }

    /* ------------------------------------------ gender / LGA (non-identity lookups) */

    public function test_an_unknown_gender_drops_the_field_and_keeps_the_row(): void
    {
        $result = $this->validate(['gender' => 'unspecified']);

        $this->assertSame([], $result['identity_errors']);
        $this->assertNull($result['payload']['gender']);
        $this->assertStringContainsStringIgnoringCase('gender', $result['dropped_fields'][0]['message']);
    }

    public function test_an_lga_outside_jigawa_drops_the_field_and_keeps_the_row(): void
    {
        $result = $this->validate(['lga' => 'ikeja']);

        $this->assertSame([], $result['identity_errors']);
        $this->assertNull($result['payload']['lga']);
        $this->assertStringContainsStringIgnoringCase('LGA', $result['dropped_fields'][0]['message']);
    }

    public function test_lga_case_and_spacing_resolve_to_the_lookup_value(): void
    {
        $result = $this->validate(['lga' => 'Birnin Kudu']);

        $this->assertSame([], $result['dropped_fields']);
        $this->assertSame('birnin_kudu', $result['payload']['lga']);
    }

    /* ------------------------------------------------------- ward (non-identity lookup) */

    /** Load a minimal, REAL division pair so the lookup has something authoritative in it. */
    private function loadWards(): void
    {
        $lga = LgaModel::query()->create([
            'code' => 'birnin_kudu',
            'name' => 'Birnin Kudu',
            'state' => 'Jigawa',
        ]);
        $lga->wards()->create(['code' => 'birnin_kudu_a', 'name' => 'Limawa']);

        $other = LgaModel::query()->create(['code' => 'dutse', 'name' => 'Dutse', 'state' => 'Jigawa']);
        $other->wards()->create(['code' => 'dutse_a', 'name' => 'Kachi']);

        app(ReferenceDataCache::class)->flush();
    }

    public function test_ward_is_free_text_while_no_division_list_has_been_loaded(): void
    {
        // GEO.1: ward names come from a maintainer-supplied dataset and are never
        // invented. Before it is loaded there is no allowed set — enforcing an empty one
        // would null the ward on every row of every import.
        $this->assertSame(0, WardModel::query()->count());

        $result = $this->validate(['ward' => 'Some Ward Nobody Has Loaded']);

        $this->assertSame([], $result['dropped_fields']);
        $this->assertSame('Some Ward Nobody Has Loaded', $result['payload']['ward']);
    }

    public function test_a_ward_in_the_loaded_list_is_accepted(): void
    {
        $this->loadWards();

        $result = $this->validate(['lga' => 'birnin kudu', 'ward' => 'limawa']);

        $this->assertSame([], $result['dropped_fields']);
        $this->assertSame([], $result['identity_errors']);
    }

    public function test_a_ward_outside_the_loaded_list_drops_the_field_and_keeps_the_row(): void
    {
        $this->loadWards();

        $result = $this->validate(['lga' => 'birnin kudu', 'ward' => 'Nowhere']);

        $this->assertSame([], $result['identity_errors'], 'ward is not an identity field');
        $this->assertNull($result['payload']['ward']);
        $this->assertStringContainsStringIgnoringCase('ward', $result['dropped_fields'][0]['message']);
    }

    public function test_a_real_ward_from_the_wrong_lga_is_refused(): void
    {
        // The mistake this rule actually catches. `Kachi` is a real ward — in Dutse, not
        // in Birnin Kudu — so a list-wide check would wave it through and file the person
        // under a place they do not live.
        $this->loadWards();

        $result = $this->validate(['lga' => 'birnin kudu', 'ward' => 'Kachi']);

        $this->assertNull($result['payload']['ward']);
    }

    public function test_a_ward_is_accepted_in_an_lga_whose_wards_were_never_supplied(): void
    {
        // The dataset covers all 27 LGAs but lists wards for only some of them — the
        // source gives prose, not names, for the rest. An LGA with zero wards loaded is
        // an ABSENCE of information, not evidence that a ward is wrong. Enforcing there
        // would drop every ward in those LGAs from every import, silently.
        $this->loadWards();

        $bare = LgaModel::query()->create(['code' => 'maigatari', 'name' => 'Maigatari', 'state' => 'Jigawa']);
        $this->assertSame(0, $bare->wards()->count());
        app(ReferenceDataCache::class)->flush();

        $result = $this->validate(['lga' => 'maigatari', 'ward' => 'A Ward Nobody Listed']);

        $this->assertSame([], $result['dropped_fields']);
        $this->assertSame('A Ward Nobody Listed', $result['payload']['ward']);
    }

    public function test_partial_coverage_does_not_weaken_an_lga_that_was_supplied(): void
    {
        // The other half of the rule: where wards ARE known, they still bind.
        $this->loadWards();
        LgaModel::query()->create(['code' => 'maigatari', 'name' => 'Maigatari', 'state' => 'Jigawa']);
        app(ReferenceDataCache::class)->flush();

        $result = $this->validate(['lga' => 'birnin kudu', 'ward' => 'Nowhere']);

        $this->assertNull($result['payload']['ward']);
    }
    /* --------------------------------------------- identity vs non-identity outcome */

    public function test_the_two_failure_kinds_are_reported_separately_on_one_row(): void
    {
        // A row can be wrong in both ways at once, and the outcome is decided by the
        // IDENTITY failure: the row is rejected whole, never partially saved.
        $result = $this->validate(['nin' => '123', 'gender' => 'unspecified']);

        $this->assertNotSame([], $result['identity_errors'], 'NIN rejects the row');
        $this->assertNotSame([], $result['dropped_fields'], 'gender is dropped');
        $this->assertSame('nin', $result['identity_errors'][0]['field']);
        $this->assertSame('gender', $result['dropped_fields'][0]['field']);
    }

    /* -------------------------------------------------------- one configurable place */

    public function test_the_identifier_length_comes_from_configuration(): void
    {
        // The shapes live in config/registry.php, not as literals scattered through the
        // rules — so a change is one edit and every ingestion door moves together.
        config(['registry.identity.nin_digits' => 8]);

        $this->assertSame([], $this->validate(['nin' => '22200000'])['identity_errors']);
        $this->assertNotSame([], $this->validate(['nin' => '22200000011'])['identity_errors']);
    }

    public function test_the_earliest_date_of_birth_comes_from_configuration(): void
    {
        config(['registry.identity.dob_earliest' => '1990-01-01']);

        $result = app(ImportRowValidator::class)->validate($this->goodRow(['date_of_birth' => '01/01/1985']));

        $this->assertNotSame([], $result['dropped_fields']);
        $this->assertStringContainsString('1990-01-01', BeneficiaryRules::messages()['date_of_birth.after']);
    }
}
