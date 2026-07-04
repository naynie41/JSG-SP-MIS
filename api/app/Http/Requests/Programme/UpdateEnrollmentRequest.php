<?php

declare(strict_types=1);

namespace App\Http\Requests\Programme;

use App\Domain\Programme\Enums\EnrollmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Update an enrollment's lifecycle (PRD FR-PRG-03) — exit/suspend/graduate.
 * Enrolling MDA only (policy).
 */
class UpdateEnrollmentRequest extends FormRequest
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
            'status' => ['required', Rule::enum(EnrollmentStatus::class)],
            'exit_reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
