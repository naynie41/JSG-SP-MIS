<?php

declare(strict_types=1);

namespace App\Domain\Registry\Imports\Adapters;

/**
 * Base adapter that maps a raw record onto canonical beneficiary fields using a
 * table of per-field aliases. Source keys are canonicalised first — group
 * prefixes (`personal/first_name`, `respondent-nin`) are stripped and the name is
 * lower-cased — so form/export column names line up with the schema.
 *
 * Concrete adapters supply their provenance, any extra aliases, and the meta
 * key(s) that carry the source's own record id.
 */
abstract class FieldMappingAdapter implements RegistrationSourceAdapter
{
    /** Canonical field => candidate source keys (already canonicalised), in priority order. */
    private const BASE_ALIASES = [
        'first_name' => ['first_name', 'firstname', 'given_name', 'givenname', 'fname'],
        'middle_name' => ['middle_name', 'middlename', 'other_name', 'othername'],
        'last_name' => ['last_name', 'lastname', 'surname', 'family_name', 'familyname', 'lname'],
        'nin' => ['nin', 'national_id', 'national_identification_number', 'nin_number'],
        'bvn' => ['bvn', 'bank_verification_number'],
        'phone' => ['phone', 'phone_number', 'phone_no', 'msisdn', 'mobile', 'telephone'],
        'date_of_birth' => ['date_of_birth', 'dob', 'birth_date', 'birthdate'],
        'gender' => ['gender', 'sex'],
        'address' => ['address', 'home_address', 'residential_address'],
        'lga' => ['lga', 'local_government', 'local_government_area'],
        'ward' => ['ward', 'ward_name'],
    ];

    public function map(array $raw): array
    {
        $canonical = $this->canonicaliseKeys($raw);

        $mapped = [];
        foreach ($this->aliases() as $field => $candidates) {
            foreach ($candidates as $candidate) {
                if (array_key_exists($candidate, $canonical) && $canonical[$candidate] !== '') {
                    $mapped[$field] = $canonical[$candidate];
                    break;
                }
            }
        }

        $mapped['original_record_id'] = $this->extractRecordId($canonical);

        return $mapped;
    }

    /**
     * The source's own record-id meta keys (already canonicalised), in priority
     * order — e.g. Kobo's `_id`, ODK's `instanceid`.
     *
     * @return list<string>
     */
    abstract protected function idKeys(): array;

    /**
     * Extra source-specific aliases, merged over the base table.
     *
     * @return array<string, list<string>>
     */
    protected function extraAliases(): array
    {
        return [];
    }

    /**
     * @return array<string, list<string>>
     */
    private function aliases(): array
    {
        return array_merge(self::BASE_ALIASES, $this->extraAliases());
    }

    /**
     * @param  array<string, string>  $canonical
     */
    private function extractRecordId(array $canonical): ?string
    {
        foreach (array_merge($this->idKeys(), ['original_record_id', 'record_id', 'id']) as $key) {
            if (! empty($canonical[$key])) {
                return $canonical[$key];
            }
        }

        return null;
    }

    /**
     * Lower-case keys and strip group prefixes so `Personal/First Name`,
     * `respondent-first_name` and `first_name` all resolve to `first_name`.
     *
     * @param  array<string, mixed>  $raw
     * @return array<string, string>
     */
    private function canonicaliseKeys(array $raw): array
    {
        $out = [];
        foreach ($raw as $key => $value) {
            $normalised = str_replace('/', '-', strtolower(trim((string) $key)));
            // Keep the leaf after the last group separator.
            if (str_contains($normalised, '-')) {
                $normalised = substr($normalised, strrpos($normalised, '-') + 1);
            }
            $out[$normalised] = is_scalar($value) ? trim((string) $value) : '';
        }

        return $out;
    }
}
