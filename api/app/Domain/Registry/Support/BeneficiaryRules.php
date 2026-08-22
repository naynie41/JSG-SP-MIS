<?php

declare(strict_types=1);

namespace App\Domain\Registry\Support;

use App\Domain\Registry\Enums\Gender;
use App\Domain\Registry\Enums\Lga;
use Illuminate\Validation\Rule;

/**
 * The canonical beneficiary-registration validation rules (PRD FR-REG-04/05),
 * shared so every ingestion path — bulk import (ImportRowValidator) and the REST
 * intake (ApiRegistrationRequest) — enforces the same mandatory fields + formats.
 */
final class BeneficiaryRules
{
    /**
     * Identity fields (PRD §9, FR-REG-05): name, phone, NIN, BVN. When one of
     * these is PRESENT but malformed the WHOLE row is rejected — an identity field
     * is never partial-saved. (Absent optional NIN/BVN/phone is still valid.)
     *
     * @var list<string>
     */
    public const IDENTITY_FIELDS = ['first_name', 'middle_name', 'last_name', 'phone', 'nin', 'bvn'];

    /**
     * Non-identity fields (PRD §9, FR-REG-09): a failure here drops/flags just that
     * field and the row still saves. All of these are nullable in the schema.
     *
     * @var list<string>
     */
    public const NON_IDENTITY_FIELDS = ['date_of_birth', 'gender', 'address', 'lga', 'ward'];

    /**
     * Delegates to {@see CanonicalSchema}, which is the authoritative declaration. The
     * two consts above stay because an admin endpoint publishes them as its response
     * shape; `RegistryRulesConsistencyTest` asserts they never drift apart from it.
     */
    public static function isIdentityField(string $field): bool
    {
        return CanonicalSchema::isIdentityField($field);
    }

    /**
     * @return array<string, mixed>
     */
    public static function forRegistration(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            // NIN/BVN are encrypted at rest; uniqueness runs on the keyed hash
            // columns (matching the partial DB indexes, WHERE <hash> IS NOT NULL).
            // NIN/BVN are encrypted at rest; uniqueness runs on the keyed hash columns.
            // The FORMAT rule reports the actual length ("has 9"), which `digits:11`
            // could not — on a 200-row file that is the difference between finding the
            // bad row and hunting for it.
            'nin' => ['nullable', new NationalIdentifier('nin'), new UniqueIdentifier('nin', self::messages()['nin.unique'])],
            'bvn' => ['nullable', new NationalIdentifier('bvn'), new UniqueIdentifier('bvn', self::messages()['bvn.unique'])],
            // Phone is an IDENTITY field and participates in fuzzy matching, so it needs
            // a real format check — `string|max:20` let "not a phone" through to the
            // matcher as a comparable value.
            'phone' => ['nullable', 'string', 'max:20', new NigerianPhone],
            'date_of_birth' => ['required', 'date', 'before:today', 'after:'.self::earliestDateOfBirth()],
            'gender' => ['required', Rule::enum(Gender::class)],
            'address' => ['nullable', 'string', 'max:500'],
            'lga' => ['required', Rule::enum(Lga::class)],
            'ward' => ['required', 'string', 'max:120'],
        ];
    }

    /** The earliest date of birth accepted as real data (config, not a literal here). */
    public static function earliestDateOfBirth(): string
    {
        return (string) config('registry.identity.dob_earliest', '1900-01-01');
    }

    /**
     * Human-readable messages shared by both entry paths.
     *
     * Every message names the FIELD and the REASON, so a row-level error report is
     * actionable without opening the rules: "Date of birth cannot be in the future"
     * tells an officer what to change; "The date of birth field must be a date before
     * today" makes them work it out.
     *
     * @return array<string, string>
     */
    public static function messages(): array
    {
        $earliest = self::earliestDateOfBirth();

        return [
            'nin.unique' => 'A beneficiary with this NIN is already registered.',
            'bvn.unique' => 'A beneficiary with this BVN is already registered.',

            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',

            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.date' => 'Date of birth must be a real date (day first, e.g. 12/03/1995).',
            'date_of_birth.before' => 'Date of birth cannot be today or in the future.',
            'date_of_birth.after' => "Date of birth must be after {$earliest} — earlier than that reads as a typo or a placeholder.",

            'gender.required' => 'Gender is required.',
            'gender.enum' => 'Gender must be one of: male, female, other.',

            'lga.required' => 'LGA is required.',
            'lga.enum' => 'LGA must be one of the 27 Jigawa Local Government Areas.',

            'ward.required' => 'Ward is required.',
        ];
    }
}
