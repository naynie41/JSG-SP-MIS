<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Registry\Enums\HouseholdRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Add an existing beneficiary to a household as a new open membership.
 */
class AddHouseholdMemberRequest extends FormRequest
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
            'beneficiary_id' => ['required', 'uuid'],
            'role_in_household' => ['required', Rule::enum(HouseholdRole::class)],
        ];
    }
}
