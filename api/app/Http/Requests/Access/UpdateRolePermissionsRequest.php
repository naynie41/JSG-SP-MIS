<?php

declare(strict_types=1);

namespace App\Http\Requests\Access;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Replace a role's permission set (the console's permission-matrix editor). An empty
 * array is valid — it revokes everything from the role.
 */
class UpdateRolePermissionsRequest extends FormRequest
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
            'permissions' => ['present', 'array'],
            'permissions.*' => ['string', 'max:80'],
        ];
    }
}
