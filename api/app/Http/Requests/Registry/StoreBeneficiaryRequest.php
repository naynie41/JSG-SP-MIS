<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Support\BeneficiaryRules;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Manual individual registration (PRD FR-REG-01/04/05). Mandatory identity fields
 * and formats are enforced here; invalid input is rejected with the standard
 * validation-error envelope. Ownership and provenance are set by the controller,
 * never accepted from the client. Rules are shared with bulk import so both paths
 * validate identically (see BeneficiaryRules).
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
        return BeneficiaryRules::forRegistration();
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return BeneficiaryRules::messages();
    }
}
