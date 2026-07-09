<?php

declare(strict_types=1);

namespace App\Http\Requests\Programme;

use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Registry\Enums\Lga;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Update an activity (PRD FR-PRG-02) — partial; owner MDA only (policy). The
 * parent programme is fixed (an activity does not move between programmes).
 */
class UpdateActivityRequest extends FormRequest
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
            'description' => ['nullable', 'string', 'max:2000'],
            'target_count' => ['nullable', 'integer', 'min:0'],
            'lga' => ['nullable', Rule::enum(Lga::class)],
            'ward' => ['nullable', 'string', 'max:100'],
            'location_description' => ['nullable', 'string', 'max:255'],
            'schedule' => ['nullable', 'array'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'budget_amount' => ['nullable', 'integer', 'min:0'],
            'funding_source' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'required', Rule::enum(ActivityStatus::class)],
        ];
    }
}
