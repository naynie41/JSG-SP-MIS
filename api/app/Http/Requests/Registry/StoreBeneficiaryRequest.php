<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Registry\Enums\Gender;
use App\Domain\Registry\Enums\Lga;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Manual individual registration (PRD FR-REG-01/04/05). Mandatory identity fields
 * and formats are enforced here; invalid input is rejected with the standard
 * validation-error envelope. Ownership and provenance are set by the controller,
 * never accepted from the client.
 */
class StoreBeneficiaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Normalise identifiers before validation so digit rules and uniqueness see
     * clean values (mirrors the model's on-save normalisation, CONVENTIONS.md §6).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nin' => $this->filled('nin') ? Beneficiary::normalizeDigits((string) $this->input('nin')) : null,
            'bvn' => $this->filled('bvn') ? Beneficiary::normalizeDigits((string) $this->input('bvn')) : null,
            'phone' => $this->filled('phone') ? Beneficiary::normalizeDigits((string) $this->input('phone')) : null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            // Unique matches the partial DB indexes (WHERE <col> IS NOT NULL),
            // giving a clean validation error instead of a 500 on collision.
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
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nin.unique' => 'A beneficiary with this NIN is already registered.',
            'bvn.unique' => 'A beneficiary with this BVN is already registered.',
        ];
    }
}
