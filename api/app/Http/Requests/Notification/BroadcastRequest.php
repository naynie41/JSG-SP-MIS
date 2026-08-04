<?php

declare(strict_types=1);

namespace App\Http\Requests\Notification;

use App\Domain\Access\Enums\RoleKey;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * A system-wide announcement. The audience may be narrowed to a role and/or an MDA;
 * omitting both reaches every active user.
 */
class BroadcastRequest extends FormRequest
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
            'subject' => ['required', 'string', 'max:160'],
            'body' => ['nullable', 'string', 'max:2000'],
            'role_key' => ['nullable', Rule::in(array_column(RoleKey::cases(), 'value'))],
            'mda_id' => ['nullable', 'string', 'uuid', 'exists:mdas,id'],
        ];
    }
}
