<?php

declare(strict_types=1);

namespace App\Domain\Registry\Imports;

use App\Domain\Registry\Support\CanonicalSchema;
use App\Domain\Registry\Support\NameSplitter;
use App\Domain\Registry\Support\NormalizationService;

/**
 * Detects a source file's columns, SUGGESTS canonical mappings, and applies a confirmed
 * mapping to produce canonical rows (CLAUDE.md §11, PRD v1.7).
 *
 * Suggestions are advisory and nothing more. The whole point of this layer is that a
 * machine guess about which column is the NIN never reaches the duplicate cascade
 * unreviewed — a wrong identity mapping does not fail loudly, it silently declares two
 * different citizens to be the same person.
 *
 * Pure: no database, no clock. Given the same headers it always proposes the same
 * mapping, so what an officer is asked to confirm does not drift between uploads.
 */
class ColumnMapper
{
    /**
     * Canonical field => candidate header spellings, best first. These are the same
     * aliases the adapters used to apply SILENTLY; here they only ever pre-fill a
     * proposal that a human still has to accept.
     *
     * @var array<string, list<string>>
     */
    private const ALIASES = [
        // One name column, split into first/last at apply() time (see NameSplitter).
        'full_name' => ['full_name', 'fullname', 'full_names', 'name', 'names', 'beneficiary_name'],
        'first_name' => ['first_name', 'firstname', 'given_name', 'givenname', 'fname', 'forename'],
        'middle_name' => ['middle_name', 'middlename', 'other_name', 'othername', 'other_names'],
        'last_name' => ['last_name', 'lastname', 'surname', 'family_name', 'familyname', 'lname'],
        'nin' => ['nin', 'national_identification_number', 'nin_number', 'national_id'],
        'bvn' => ['bvn', 'bank_verification_number', 'bvn_number'],
        'phone' => ['phone', 'phone_number', 'phone_no', 'msisdn', 'mobile', 'mobile_number', 'telephone'],
        'date_of_birth' => ['date_of_birth', 'dob', 'birth_date', 'birthdate', 'date_of_birth_dd_mm_yyyy'],
        'gender' => ['gender', 'sex'],
        'address' => ['address', 'home_address', 'residential_address'],
        // "LG" and "Council" are both how MDAs write the Local Government.
        'lga' => ['lga', 'lg', 'local_government', 'local_government_area', 'council'],
        'ward' => ['ward', 'ward_name'],
        'household_ref' => ['household_id', 'household_ref', 'household_code', 'household', 'hh_id'],
        'household_role' => ['household_role', 'relationship', 'role_in_household', 'hh_role'],
        'household_head' => ['household_head', 'is_head', 'head', 'hh_head'],
        'original_record_id' => ['original_record_id', 'record_id', 'uuid', '_id', '_uuid', 'instanceid', '__id', 'id'],
    ];

    /**
     * Aliases that must never be auto-suggested with high confidence because the header
     * is genuinely ambiguous about WHICH identifier it holds. `national_id` is the
     * motivating case: it is used for NIN, for a voter's card, and for a state ID.
     *
     * @var list<string>
     */
    private const AMBIGUOUS = ['national_id', 'id', 'head', 'household'];

    public function __construct(private readonly NormalizationService $normalizer = new NormalizationService) {}

    /**
     * A stable fingerprint of a file's SHAPE — its set of columns, order-independent.
     *
     * A saved template is offered only for a file with the same signature, so when an
     * MDA changes its export the old mapping is not silently reapplied to columns that
     * have moved or been renamed.
     *
     * @param  list<string>  $headers
     */
    public function signature(array $headers): string
    {
        $canonical = array_values(array_unique(array_filter(array_map(
            fn (string $header): string => $this->canonicaliseHeader($header),
            $headers,
        ))));
        sort($canonical);

        return hash('sha256', implode('|', $canonical));
    }

    /**
     * Propose a canonical field for each header. Advisory only.
     *
     * @param  list<string>  $headers
     * @return array<string, array{header: ?string, confidence: string, reason: string}>
     *                                                                                   keyed by canonical field
     */
    public function suggest(array $headers): array
    {
        $suggestions = [];
        $taken = [];

        /*
         * Two passes, exact before fuzzy, across ALL fields. Resolving field-by-field
         * lets an early field's weak guess consume a header that a later field matches
         * EXACTLY — `hh_id` fuzzily claiming `_id` before `original_record_id` can take
         * it. A confident match should never lose to a speculative one declared earlier.
         */
        foreach ([true, false] as $exactPass) {
            foreach (CanonicalSchema::mappableFields() as $field) {
                if (isset($suggestions[$field])) {
                    continue;
                }

                $match = $exactPass
                    ? $this->exactHeaderFor($field, $headers, $taken)
                    : $this->fuzzyHeaderFor($field, $headers, $taken);

                if ($match === null) {
                    continue;
                }

                [$header, $confidence, $reason] = $match;
                $taken[] = $header;
                $suggestions[$field] = ['header' => $header, 'confidence' => $confidence, 'reason' => $reason];
            }
        }

        foreach (CanonicalSchema::mappableFields() as $field) {
            $suggestions[$field] ??= ['header' => null, 'confidence' => 'none', 'reason' => 'No column resembled this field.'];
        }

        return $suggestions;
    }

    /**
     * A header whose canonicalised name IS one of the field's aliases.
     *
     * @param  list<string>  $headers
     * @param  list<string>  $taken
     * @return array{0: string, 1: string, 2: string}|null
     */
    private function exactHeaderFor(string $field, array $headers, array $taken): ?array
    {
        foreach (self::ALIASES[$field] ?? [] as $alias) {
            foreach ($headers as $header) {
                if (in_array($header, $taken, true) || $this->canonicaliseHeader($header) !== $alias) {
                    continue;
                }

                return in_array($alias, self::AMBIGUOUS, true)
                    ? [$header, 'low', "“{$header}” often means something else — confirm which identifier it holds."]
                    : [$header, 'high', "Header matches “{$alias}”."];
            }
        }

        return null;
    }

    /**
     * A header that CONTAINS an alias — `beneficiary_phone_number` for `phone`.
     *
     * Only that direction, and only for aliases of a real length. The reverse (an alias
     * containing the header) matches on fragments: `hh_id` "resembles" a column called
     * `_id`, which is how a household reference ends up claiming a record id. Always
     * reported as LOW — this is the guess most likely to be wrong.
     *
     * @param  list<string>  $headers
     * @param  list<string>  $taken
     * @return array{0: string, 1: string, 2: string}|null
     */
    private function fuzzyHeaderFor(string $field, array $headers, array $taken): ?array
    {
        foreach (self::ALIASES[$field] ?? [] as $alias) {
            if (mb_strlen($alias) < 4) {
                continue;
            }

            foreach ($headers as $header) {
                if (in_array($header, $taken, true)) {
                    continue;
                }
                if (str_contains($this->canonicaliseHeader($header), $alias)) {
                    return [$header, 'low', "“{$header}” resembles “{$alias}”."];
                }
            }
        }

        return null;
    }

    /**
     * Apply a CONFIRMED mapping to one raw row.
     *
     * A canonical field present in the map with a null header is confirmed ABSENT and
     * stays absent; a field missing from the map entirely is unmapped and also yields
     * null. The raw row is never modified — the returned array is a new canonical
     * representation, and the uploaded file itself is only ever read.
     *
     * @param  array<string, string>  $rawRow  header-keyed source values
     * @param  array<string, string|null>  $columnMap  canonical field => source header
     * @return array<string, string|null>
     */
    public function apply(array $rawRow, array $columnMap): array
    {
        $read = function (string $field) use ($rawRow, $columnMap): ?string {
            $header = $columnMap[$field] ?? null;
            $value = $header === null ? null : ($rawRow[$header] ?? null);

            return ($value === null || trim((string) $value) === '') ? null : (string) $value;
        };

        $canonical = [];
        foreach (CanonicalSchema::allFields() as $field) {
            $canonical[$field] = $read($field);
        }

        // A single mapped name column, split into first/last (see NameSplitter). Applied
        // PER ROW and only where the explicit columns left a gap, so a file that carries
        // both — or carries separate names for some people and one field for others —
        // keeps whatever it actually stated. `full_name` is never stored under that name.
        $split = NameSplitter::split($read('full_name'));
        $canonical['first_name'] ??= $split['first_name'];
        $canonical['last_name'] ??= $split['last_name'];

        return $canonical;
    }

    /**
     * Which confirmation-required fields the given map has NOT settled (CLAUDE.md §11).
     *
     * "Settled" means the key is PRESENT — pointing at a header, or explicitly at null
     * to say this source does not carry it. A key that is simply missing is unanswered,
     * and that is what this returns.
     *
     * @param  array<string, string|null>  $columnMap
     * @return list<string>
     */
    public function unconfirmedIdentityFields(array $columnMap): array
    {
        return array_values(array_filter(
            CanonicalSchema::confirmationRequiredFields(),
            static fn (string $field): bool => ! array_key_exists($field, $columnMap),
        ));
    }

    /**
     * Headers named in a map that the file does not actually contain — a stale template
     * applied to a changed export, which would silently map the field to nothing.
     *
     * @param  array<string, string|null>  $columnMap
     * @param  list<string>  $headers
     * @return list<string>
     */
    public function unknownHeaders(array $columnMap, array $headers): array
    {
        $known = array_map(fn (string $h): string => $this->canonicaliseHeader($h), $headers);

        return array_values(array_unique(array_filter(
            array_map(
                fn (?string $header): ?string => $header,
                array_filter($columnMap, static fn (?string $header): bool => $header !== null),
            ),
            fn (string $header): bool => ! in_array($this->canonicaliseHeader($header), $known, true),
        )));
    }

    /** Lower-case, strip group prefixes and fold separators, so headers compare sanely. */
    private function canonicaliseHeader(string $header): string
    {
        $name = $header;
        if (str_contains($name, '/')) {
            $parts = explode('/', $name);
            $name = (string) end($parts);
        }

        return (string) $this->normalizer->enumKey($name);
    }
}
