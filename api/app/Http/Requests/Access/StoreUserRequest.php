<?php

declare(strict_types=1);

namespace App\Http\Requests\Access;

use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Support\PasswordRules;
use App\Http\Requests\Access\Concerns\ValidatesUserAssignment;
use Illuminate\Contracts\Validation\Validator;
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => PasswordRules::default(),
            // Nullable here; the role decides whether it is actually required, checked
            // in withValidator() because an ABSENT field skips rules entirely.
            'mda_id' => [
                'nullable',
                'uuid',
                'exists:mdas,id',
                $this->accessibleMdaRule(),
            ],
            'role_id' => ['required', 'uuid', 'exists:roles,id', $this->assignableRoleRule()],
            'status' => ['sometimes', Rule::enum(UserStatus::class)],
        ];
    }

    /** An MDA-scoped role must have an MDA; a state-level role must not (FR-UAM-02/03). */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $this->assertMdaMatchesRole(
                $validator,
                $this->input('role_id'),
                $this->input('mda_id'),
            );
        });
    }
}
