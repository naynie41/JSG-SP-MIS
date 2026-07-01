<?php

declare(strict_types=1);

namespace App\Http\Requests\Access;

use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Support\PasswordRules;
use App\Http\Requests\Access\Concerns\ValidatesUserAssignment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
        $actor = $this->user();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => PasswordRules::default(),
            'mda_id' => [
                $actor !== null && $actor->canAccessAllMdas() ? 'nullable' : 'required',
                'uuid',
                'exists:mdas,id',
                $this->accessibleMdaRule(),
            ],
            'role_id' => ['required', 'uuid', 'exists:roles,id', $this->assignableRoleRule()],
            'status' => ['sometimes', Rule::enum(UserStatus::class)],
        ];
    }
}
