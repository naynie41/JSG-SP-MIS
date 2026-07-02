<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Registry\Enums\HouseholdRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Move a beneficiary into the target household (closing any current membership).
 * The role defaults to Other when not supplied.
 */
class MoveHouseholdMemberRequest extends FormRequest
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
            'role_in_household' => ['nullable', Rule::enum(HouseholdRole::class)],
        ];
    }
}
