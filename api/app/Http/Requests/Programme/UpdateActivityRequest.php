<?php

declare(strict_types=1);

namespace App\Http\Requests\Programme;

use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Rules\IsFundingPartner;
use App\Http\Requests\Programme\Concerns\ValidatesLocationSet;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Update an activity (PRD FR-PRG-02) — partial; owner MDA only (policy). The
 * parent programme is fixed (an activity does not move between programmes), and
 * `involves_beneficiaries` is immutable after creation (changing it would orphan
 * interventions or bypass the mandatory upload), so it is intentionally absent here.
 */
class UpdateActivityRequest extends FormRequest
{
    use ValidatesLocationSet;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return $this->locationSetMessages();
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(fn (Validator $v) => $this->validateLocationSet($v));
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'target_beneficiaries' => ['nullable', 'integer', 'min:1'],
            // Submitting `locations` REPLACES the whole set (see ActivityLocationService);
            // omitting it leaves the existing set untouched.
            ...$this->locationSetRules(),
            'location_description' => ['nullable', 'string', 'max:255'],
            'schedule' => ['nullable', 'array'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'budget_amount' => ['nullable', 'integer', 'min:0'],
            'funding_source' => ['nullable', 'string', 'max:255'],
            'funding_partner_id' => ['nullable', 'uuid', new IsFundingPartner],
            'status' => ['sometimes', 'required', Rule::enum(ActivityStatus::class)],
        ];
    }
}
