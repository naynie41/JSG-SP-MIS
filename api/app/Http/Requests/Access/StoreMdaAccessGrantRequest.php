<?php

declare(strict_types=1);

namespace App\Http\Requests\Access;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMdaAccessGrantRequest extends FormRequest
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
            'user_id' => ['required', 'uuid', 'exists:users,id'],
            'mda_id' => [
                'required',
                'uuid',
                'exists:mdas,id',
                /*
                 * One ACTIVE grant per (user, MDA) — matching the partial unique index.
                 * The `revoked_at` filter is load-bearing now that revocation retains
                 * the row: without it, a single past grant would permanently bar that
                 * user from ever being granted access to that MDA again.
                 */
                Rule::unique('mda_access_grants', 'mda_id')
                    ->where(fn ($query) => $query
                        ->where('user_id', $this->input('user_id'))
                        ->whereNull('revoked_at')),
            ],
            'reason' => ['nullable', 'string', 'max:500'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ];
    }
}
