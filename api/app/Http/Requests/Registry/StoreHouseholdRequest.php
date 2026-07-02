<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Registry\Enums\HouseholdRole;
use App\Domain\Registry\Enums\Lga;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Create a household (PRD FR-REG-01 household path), optionally with initial
 * members. Ownership/provenance are set by the controller, never by the client.
 */
class StoreHouseholdRequest extends FormRequest
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
            'address' => ['nullable', 'string', 'max:500'],
            'lga' => ['required', Rule::enum(Lga::class)],
            'ward' => ['required', 'string', 'max:120'],
            'members' => ['nullable', 'array'],
            'members.*.beneficiary_id' => ['required', 'uuid'],
            'members.*.role_in_household' => ['required', Rule::enum(HouseholdRole::class)],
            'head_beneficiary_id' => ['nullable', 'uuid'],
        ];
    }
}
