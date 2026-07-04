<?php

declare(strict_types=1);

namespace App\Http\Requests\Benefit;

use App\Domain\Benefit\Enums\BenefitType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Create/replace a double-dipping rule (PRD FR-BEN-05): a window plus the benefit
 * types it applies to (empty = all).
 */
class StoreDoubleDippingRuleRequest extends FormRequest
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
        $partial = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'name' => [$partial ? 'sometimes' : 'required', 'string', 'max:255'],
            'period_days' => [$partial ? 'sometimes' : 'required', 'integer', 'min:1', 'max:3650'],
            'benefit_types' => ['nullable', 'array'],
            'benefit_types.*' => [Rule::enum(BenefitType::class)],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }
}
