<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Support\BeneficiaryRules;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Inbound REST registration intake (PRD FR-REG-02, source = "api"). Uses the
 * shared registration rules so an API submission is validated identically to a
 * manual entry, and additionally requires the caller's own record id so every
 * ingested record stays traceable to origin.
 */
class ApiRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Normalise identifiers before validation (mirrors the model/manual path).
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
            ...BeneficiaryRules::forRegistration(),
            'original_record_id' => ['required', 'string', 'max:255'],
            // Optional explicit idempotency key; defaults to original_record_id.
            'idempotency_key' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return BeneficiaryRules::messages();
    }
}
