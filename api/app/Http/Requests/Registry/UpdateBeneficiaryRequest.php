<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Registry\Enums\BeneficiaryStatus;
use App\Domain\Registry\Enums\Gender;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBeneficiaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Normalise identifiers before validation so digit rules see clean values
     * (mirrors the model's on-save normalisation, CONVENTIONS.md §6).
     */
    protected function prepareForValidation(): void
    {
        $this->merge(array_filter([
            'nin' => $this->filled('nin') ? Beneficiary::normalizeDigits((string) $this->input('nin')) : null,
            'bvn' => $this->filled('bvn') ? Beneficiary::normalizeDigits((string) $this->input('bvn')) : null,
            'phone' => $this->filled('phone') ? Beneficiary::normalizeDigits((string) $this->input('phone')) : null,
        ], fn ($value) => $value !== null));
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'nin' => ['nullable', 'digits:11'],
            'bvn' => ['nullable', 'digits:11'],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::enum(Gender::class)],
            'address' => ['nullable', 'string', 'max:500'],
            'lga' => ['nullable', 'string', 'max:120'],
            'ward' => ['nullable', 'string', 'max:120'],
            'status' => ['sometimes', Rule::enum(BeneficiaryStatus::class)],
        ];
    }
}
