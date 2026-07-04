<?php

declare(strict_types=1);

namespace App\Http\Requests\Benefit;

use App\Domain\Benefit\Enums\BenefitType;
use App\Domain\Benefit\Enums\VerificationMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Record a benefit delivery (PRD FR-BEN-02, §8.3). Records delivery only — money
 * is never moved; `monetary_value` is descriptive (integer minor units, kobo).
 * Optionally verifies inline (FR-BEN-04).
 */
class RecordBenefitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'beneficiary_id' => ['required', 'uuid', 'exists:beneficiaries,id'],
            'programme_id' => ['required', 'uuid', 'exists:programmes,id'],
            'activity_id' => ['nullable', 'uuid', 'exists:activities,id'],
            'benefit_type' => ['required', Rule::enum(BenefitType::class)],
            'quantity' => ['nullable', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:50'],
            'monetary_value' => ['nullable', 'integer', 'min:0'],
            'funding_source' => ['nullable', 'string', 'max:255'],
            'delivery_date' => ['required', 'date', 'before_or_equal:today'],
            'verification_method' => ['nullable', Rule::enum(VerificationMethod::class)],
            'verification_reference' => ['nullable', 'string', 'max:255', 'required_if:verification_method,signature'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
