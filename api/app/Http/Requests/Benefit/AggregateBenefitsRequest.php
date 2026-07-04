<?php

declare(strict_types=1);

namespace App\Http\Requests\Benefit;

use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Enums\BenefitType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Aggregate the benefit ledger by a dimension with optional filters (PRD FR-BEN-03).
 * Results are always MDA-scoped to the caller (oversight sees all).
 */
class AggregateBenefitsRequest extends FormRequest
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
            'group_by' => ['required', Rule::in(['programme', 'activity', 'mda', 'lga', 'ward', 'beneficiary', 'benefit_type'])],
            'programme_id' => ['nullable', 'uuid'],
            'activity_id' => ['nullable', 'uuid'],
            'mda_id' => ['nullable', 'uuid'],
            'benefit_type' => ['nullable', Rule::enum(BenefitType::class)],
            'status' => ['nullable', Rule::enum(BenefitStatus::class)],
            'lga' => ['nullable', 'string', 'max:100'],
            'ward' => ['nullable', 'string', 'max:100'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ];
    }
}
