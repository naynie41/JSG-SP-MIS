<?php

declare(strict_types=1);

namespace App\Domain\Registry\Support;

use App\Domain\Registry\Enums\Gender;
use App\Domain\Registry\Enums\Lga;
use Illuminate\Validation\Rule;

/**
 * The canonical beneficiary-registration validation rules (PRD FR-REG-04/05),
 * shared so manual registration (StoreBeneficiaryRequest) and bulk import
 * (ImportRowValidator) enforce exactly the same mandatory fields and formats.
 */
final class BeneficiaryRules
{
    /**
     * @return array<string, mixed>
     */
    public static function forRegistration(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            // Unique matches the partial DB indexes (WHERE <col> IS NOT NULL).
            'nin' => ['nullable', 'digits:11', Rule::unique('beneficiaries', 'nin')],
            'bvn' => ['nullable', 'digits:11', Rule::unique('beneficiaries', 'bvn')],
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
