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
                // One active grant row per (user, MDA).
                Rule::unique('mda_access_grants', 'mda_id')
                    ->where(fn ($query) => $query->where('user_id', $this->input('user_id'))),
            ],
            'reason' => ['nullable', 'string', 'max:500'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ];
    }
}
