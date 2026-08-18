<?php

declare(strict_types=1);

namespace App\Domain\Registry\Support;

use App\Domain\Registry\Imports\ColumnMapper;

/**
 * Splits a single full-name column into first/last name.
 *
 * Many MDA exports carry one `Name` column rather than separate given/surname fields.
 * Mapping such a column to BOTH `first_name` and `last_name` is what produced records
 * reading "Rekiya Bagwai Rekiya Bagwai"; mapping it to one leaves the other empty and
 * the row fails validation. So the column is mapped once, to `full_name`, and split here.
 *
 * The rule (stakeholder decision, not inferred):
 *
 *   "Amina"                → first: Amina        last: (none)
 *   "Rekiya Bagwai"        → first: Rekiya       last: Bagwai
 *   "Barira Sadau Barde"   → first: Barira       last: Sadau Barde
 *   "A B C D"              → first: A            last: B C D
 *
 * The first token is the first name; EVERYTHING after it is the last name. Note that
 * `middle_name` is deliberately never populated by the split — the instruction is that a
 * middle token belongs to the surname, and inferring otherwise would silently disagree.
 *
 * A single token yields no last name rather than a guessed one. `last_name` is required
 * (and is the fuzzy blocking key), so such a row is rejected by validation — which is
 * the honest outcome: SP-MIS cannot invent a surname it was not given.
 *
 * Explicit `first_name` / `last_name` columns always win; this only fills what they left
 * empty. See {@see ColumnMapper::apply()}.
 */
final class NameSplitter
{
    /**
     * @return array{first_name: string|null, last_name: string|null}
     */
    public static function split(?string $fullName): array
    {
        $tokens = self::tokenise($fullName);

        if ($tokens === []) {
            return ['first_name' => null, 'last_name' => null];
        }

        $first = array_shift($tokens);

        return [
            'first_name' => $first,
            // Every remaining token, joined — "Sadau Barde" stays one surname rather
            // than losing "Sadau" to a middle-name field the rule does not use.
            'last_name' => $tokens === [] ? null : implode(' ', $tokens),
        ];
    }

    /**
     * Name tokens, with punctuation-free whitespace collapsed.
     *
     * Splits on any whitespace run (files arrive with double spaces, tabs and non-breaking
     * spaces from spreadsheet exports), and drops empty tokens so " Ada  Okoye " does not
     * yield a blank first name.
     *
     * @return list<string>
     */
    private static function tokenise(?string $fullName): array
    {
        if ($fullName === null) {
            return [];
        }

        // \x{00A0} is the non-breaking space Excel exports leave behind; \s alone misses it.
        $normalised = (string) preg_replace('/[\s\x{00A0}]+/u', ' ', $fullName);

        return array_values(array_filter(
            explode(' ', trim($normalised)),
            static fn (string $token): bool => $token !== '',
        ));
    }
}
