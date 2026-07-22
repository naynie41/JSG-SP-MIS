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

    public static function isIdentityField(string $field): bool
    {
        return in_array($field, self::IDENTITY_FIELDS, true);
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
            'nin' => ['nullable', 'digits:11', new UniqueIdentifier('nin', self::messages()['nin.unique'])],
            'bvn' => ['nullable', 'digits:11', new UniqueIdentifier('bvn', self::messages()['bvn.unique'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'address' => ['nullable', 'string', 'max:500'],
            'lga' => ['required', Rule::enum(Lga::class)],
            'ward' => ['required', 'string', 'max:120'],
        ];
    }

    /**
     * Human-readable messages shared by both entry paths.
     *
     * @return array<string, string>
     */
    public static function messages(): array
    {
        return [
            'nin.unique' => 'A beneficiary with this NIN is already registered.',
            'bvn.unique' => 'A beneficiary with this BVN is already registered.',
        ];
    }
}
