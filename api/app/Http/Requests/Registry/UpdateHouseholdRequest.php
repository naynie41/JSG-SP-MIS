<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Registry\Enums\Lga;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Edit a household's core fields (owner MDA only — enforced by policy). Membership
 * and head changes have their own dedicated endpoints.
 */
class UpdateHouseholdRequest extends FormRequest
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
            'lga' => ['sometimes', 'required', Rule::enum(Lga::class)],
            'ward' => ['sometimes', 'required', 'string', 'max:120'],
        ];
    }
}
