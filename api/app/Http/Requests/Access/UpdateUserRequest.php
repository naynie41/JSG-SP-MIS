<?php

declare(strict_types=1);

namespace App\Http\Requests\Access;

use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\User;
use App\Http\Requests\Access\Concerns\ValidatesUserAssignment;
use Illuminate\Contracts\Validation\Validator;
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

    /**
     * Validate the role/MDA pairing that RESULTS from this update (FR-UAM-02/03).
     *
     * An update is partial, so neither field alone tells the truth: changing role to
     * MDA Admin without sending `mda_id` must be judged against the MDA the user
     * already has, and changing to a state-level role while the user still holds an
     * MDA must be refused rather than quietly leaving them scoped to it.
     *
     * So the check runs on the EFFECTIVE values — payload where present, current
     * record otherwise — instead of on the payload in isolation.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $user = $this->route('user');

            if (! $user instanceof User) {
                return;
            }

            $roleId = $this->has('role_id') ? (string) $this->input('role_id') : $user->role_id;
            $mdaId = $this->has('mda_id') ? $this->input('mda_id') : $user->mda_id;

            $this->assertMdaMatchesRole($validator, $roleId, $mdaId);
        });
    }
}
