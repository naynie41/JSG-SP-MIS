<?php

declare(strict_types=1);

namespace App\Http\Requests\Programme;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Enroll a single already-registered beneficiary OR household into a programme
 * (PRD FR-PRG-03). Exactly one target; it must match the programme type (checked
 * in the controller). This never registers a new beneficiary.
 */
class StoreEnrollmentRequest extends FormRequest
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
            'beneficiary_id' => ['nullable', 'uuid', 'exists:beneficiaries,id'],
            'household_id' => ['nullable', 'uuid', 'exists:households,id'],
            'activity_id' => ['nullable', 'uuid', 'exists:activities,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            $hasBeneficiary = $this->filled('beneficiary_id');
            $hasHousehold = $this->filled('household_id');
            if ($hasBeneficiary === $hasHousehold) {
                $v->errors()->add('beneficiary_id', 'Provide exactly one of a beneficiary or a household.');
            }
        });
    }
}
