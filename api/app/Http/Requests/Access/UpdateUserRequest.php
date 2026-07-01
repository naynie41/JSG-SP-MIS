<?php

declare(strict_types=1);

namespace App\Http\Requests\Access;

use App\Domain\Access\Enums\UserStatus;
use App\Http\Requests\Access\Concerns\ValidatesUserAssignment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    use ValidatesUserAssignment;

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
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'mda_id' => ['sometimes', 'nullable', 'uuid', 'exists:mdas,id', $this->accessibleMdaRule()],
            'role_id' => ['sometimes', 'required', 'uuid', 'exists:roles,id', $this->assignableRoleRule()],
            'status' => ['sometimes', Rule::enum(UserStatus::class)],
        ];
    }
}
