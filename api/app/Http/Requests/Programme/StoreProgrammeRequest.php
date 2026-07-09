<?php

declare(strict_types=1);

namespace App\Http\Requests\Programme;

use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Enums\ProgrammeType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Create a GLOBAL catalog programme (PRD §10). Only type-level attributes live
 * here — name, objective, type, benefit category and standard eligibility. Budget,
 * funding and period belong to the Activity, not the programme. Authorization
 * (catalog admin only) is handled in the controller/policy.
 */
class StoreProgrammeRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'objective' => ['nullable', 'string', 'max:2000'],
            'type' => ['required', Rule::enum(ProgrammeType::class)],
            'benefit_category' => ['nullable', 'string', 'max:255'],
            'eligibility' => ['nullable', 'array'],
            'eligibility.*' => ['array'],
            'enforce_eligibility' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::enum(ProgrammeStatus::class)],
        ];
    }
}
