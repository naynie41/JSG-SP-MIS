<?php

declare(strict_types=1);

namespace App\Http\Requests\Programme;

use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Enums\ProgrammeType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Update a programme (PRD FR-PRG-01) — partial; owner MDA only (policy). Fields
 * are `sometimes` so a partial patch validates only what is present.
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
            'eligibility' => ['nullable', 'array'],
            'eligibility.*' => ['array'],
            'enforce_eligibility' => ['nullable', 'boolean'],
            'funding_source' => ['nullable', 'string', 'max:255'],
            'budget_amount' => ['nullable', 'integer', 'min:0'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'status' => ['sometimes', 'required', Rule::enum(ProgrammeStatus::class)],
        ];
    }
}
