<?php

declare(strict_types=1);

namespace App\Http\Requests\Programme;

use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Registry\Enums\Lga;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Create an activity under a programme (PRD FR-PRG-02). The parent programme must
 * exist; ownership is enforced in the controller/policy. Monetary amounts are
 * integer minor units (kobo, NGN).
 */
class StoreActivityRequest extends FormRequest
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
            'programme_id' => ['required', 'uuid', 'exists:programmes,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'target_count' => ['nullable', 'integer', 'min:0'],
            'lga' => ['nullable', Rule::enum(Lga::class)],
            'ward' => ['nullable', 'string', 'max:100'],
            'location_description' => ['nullable', 'string', 'max:255'],
            'schedule' => ['nullable', 'array'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'budget_amount' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', Rule::enum(ActivityStatus::class)],
        ];
    }
}
