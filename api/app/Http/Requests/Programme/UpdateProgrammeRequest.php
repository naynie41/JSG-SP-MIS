<?php

declare(strict_types=1);

namespace App\Http\Requests\Programme;

use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Enums\ProgrammeType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Update a catalog programme (PRD §10) — partial; catalog admin only (policy).
 * Only type-level attributes are editable; budget/funding/period live on activities.
 */
class UpdateProgrammeRequest extends FormRequest
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
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'objective' => ['nullable', 'string', 'max:2000'],
            'type' => ['sometimes', 'required', Rule::enum(ProgrammeType::class)],
            'benefit_category' => ['nullable', 'string', 'max:255'],
            'eligibility' => ['nullable', 'array'],
            'eligibility.*' => ['array'],
            'enforce_eligibility' => ['nullable', 'boolean'],
            'status' => ['sometimes', 'required', Rule::enum(ProgrammeStatus::class)],
        ];
    }
}
