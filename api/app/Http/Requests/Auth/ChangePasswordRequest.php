<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Domain\Access\Support\PasswordRules;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            // Verify the current password against the Sanctum-authenticated user.
            'current_password' => ['required', 'string', 'current_password:sanctum'],
            // New password must meet the policy and differ is enforced by the controller.
            'password' => PasswordRules::default(),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'current_password.current_password' => 'The current password is incorrect.',
        ];
    }
}
