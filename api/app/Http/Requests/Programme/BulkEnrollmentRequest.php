<?php

declare(strict_types=1);

namespace App\Http\Requests\Programme;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Bulk-enroll a set of already-registered beneficiaries OR households into a
 * programme (PRD FR-PRG-03). Exactly one non-empty list; it must match the
 * programme type (checked in the controller). Per-target outcomes are returned.
 */
class BulkEnrollmentRequest extends FormRequest
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
            'beneficiary_ids' => ['nullable', 'array', 'max:1000'],
            'beneficiary_ids.*' => ['uuid', 'distinct', 'exists:beneficiaries,id'],
            'household_ids' => ['nullable', 'array', 'max:1000'],
            'household_ids.*' => ['uuid', 'distinct', 'exists:households,id'],
            'activity_id' => ['nullable', 'uuid', 'exists:activities,id'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            $hasBeneficiaries = count((array) $this->input('beneficiary_ids', [])) > 0;
            $hasHouseholds = count((array) $this->input('household_ids', [])) > 0;
            if ($hasBeneficiaries === $hasHouseholds) {
                $v->errors()->add('beneficiary_ids', 'Provide exactly one non-empty list of beneficiary_ids or household_ids.');
            }
        });
    }
}
