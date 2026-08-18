<?php

declare(strict_types=1);

namespace Tests\Unit\Registry;

use App\Domain\Registry\Support\NameSplitter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Splitting one name column into first/last name. Pure, so tested as a unit.
 *
 * The rule is a stakeholder decision, not an inference: the FIRST token is the first
 * name, and everything after it is the last name. Getting this wrong is not cosmetic —
 * `last_name` is the fuzzy blocking key, so a mis-split name changes who the duplicate
 * cascade compares a person against.
 */
class NameSplitterTest extends TestCase
{
    /** @return list<array{0: string|null, 1: string|null, 2: string|null}> */
    public static function names(): array
    {
        return [
            // The two-name case: exactly as written.
            ['Rekiya Bagwai', 'Rekiya', 'Bagwai'],

            // Three names: the middle token belongs to the SURNAME, not to middle_name.
            ['Barira Sadau Barde', 'Barira', 'Sadau Barde'],

            // Four or more follows the same rule rather than becoming a special case.
            ['Nura Bichi Musa Adamu', 'Nura', 'Bichi Musa Adamu'],

            // One token: no surname is invented. `last_name` is required, so the row is
            // rejected downstream — the honest outcome for a name SP-MIS was not given.
            ['Amina', 'Amina', null],

            // Spreadsheet whitespace: double spaces, tabs, leading/trailing padding and
            // the non-breaking space Excel exports leave behind.
            ['  Ada   Okoye  ', 'Ada', 'Okoye'],
            ["Ada\tOkoye", 'Ada', 'Okoye'],
            ["Ada\u{00A0}Okoye", 'Ada', 'Okoye'],

            // Nothing to split.
            ['', null, null],
            ['   ', null, null],
            [null, null, null],
        ];
    }

    #[DataProvider('names')]
    public function test_it_splits_a_full_name(?string $input, ?string $first, ?string $last): void
    {
        $this->assertSame(
            ['first_name' => $first, 'last_name' => $last],
            NameSplitter::split($input),
        );
    }

    public function test_it_never_produces_a_duplicated_name(): void
    {
        // The defect this exists to prevent: one name column mapped to BOTH fields,
        // producing "Rekiya Bagwai Rekiya Bagwai" on 220 records.
        $split = NameSplitter::split('Rekiya Bagwai');

        $this->assertNotSame($split['first_name'], $split['last_name']);
        $this->assertSame('Rekiya Bagwai', $split['first_name'].' '.$split['last_name']);
    }

    public function test_the_original_name_is_always_reconstructable(): void
    {
        // Nothing is dropped: first + last must rebuild what the source said, so no token
        // is silently lost on the way in.
        foreach (['Amina', 'Rekiya Bagwai', 'Barira Sadau Barde', 'A B C D E'] as $name) {
            $split = NameSplitter::split($name);
            $rebuilt = trim(($split['first_name'] ?? '').' '.($split['last_name'] ?? ''));

            $this->assertSame($name, $rebuilt);
        }
    }

    public function test_casing_and_punctuation_are_left_alone(): void
    {
        // Splitting is not cleaning. The stored value stays exactly as the source wrote
        // it; comparison-time normalization is a separate concern (NormalizationService).
        $this->assertSame(
            ['first_name' => 'MOHAMMED', 'last_name' => "Al-Amin O'Brien"],
            NameSplitter::split("MOHAMMED Al-Amin O'Brien"),
        );
    }
}
