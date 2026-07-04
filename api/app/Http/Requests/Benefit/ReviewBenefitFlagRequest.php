<?php

declare(strict_types=1);

namespace App\Http\Requests\Benefit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Review a double-dipping flag (PRD FR-BEN-05): confirm (real overlap) or dismiss
 * (acceptable). Never blocks a delivery — this only records the review outcome.
 */
class ReviewBenefitFlagRequest extends FormRequest
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
            'status' => ['required', Rule::in(['confirmed', 'dismissed'])],
            'review_note' => ['nullable', 'string', 'max:500'],
        ];
    }
}
