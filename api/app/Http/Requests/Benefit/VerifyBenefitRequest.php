<?php

declare(strict_types=1);

namespace App\Http\Requests\Benefit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Verify a recorded benefit delivery (PRD FR-BEN-04). The method must be a real
 * verification method (not `none`); a signature requires its reference. Methods
 * whose external access is unavailable are rejected at execution with a clear
 * message.
 */
class VerifyBenefitRequest extends FormRequest
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
            'verification_method' => ['required', Rule::in(['field_confirmation', 'signature', 'otp', 'biometric'])],
            'verification_reference' => ['nullable', 'string', 'max:255', 'required_if:verification_method,signature'],
        ];
    }
}
