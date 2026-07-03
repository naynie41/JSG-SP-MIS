<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Raise a request-to-serve an existing (non-owned) beneficiary from a search
 * result (PRD FR-DUP-05, FR-OWN-03). The target is identified by id; ownership is
 * never transferred.
 */
class RaiseServeRequestRequest extends FormRequest
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
            'beneficiary_id' => ['required', 'uuid'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
