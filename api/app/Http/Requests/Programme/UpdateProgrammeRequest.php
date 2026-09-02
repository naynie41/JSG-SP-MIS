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
            // Archived is EXCLUDED here on purpose. Archiving has rules — it is
            // blocked while active activities run, and it records who/when/why — and
            // all of that lives in ProgrammeArchiver behind POST /archive. Leaving
            // `archived` settable on a general update made the block bypassable by
            // sending a plain PATCH, which would have left the whole feature
            // decorative. Un-archiving likewise goes through POST /unarchive.
            'status' => [
                'sometimes',
                'required',
                Rule::enum(ProgrammeStatus::class)->except([ProgrammeStatus::Archived]),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status' => 'To archive a programme use the archive action, which checks for active activities first.',
        ];
    }
}
