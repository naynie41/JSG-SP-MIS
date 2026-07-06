<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Raise a Service Request against an existing (non-owned) beneficiary (PRD §12,
 * FR-OWN-06), typically from a serve-search result. The target is identified by
 * id; ownership is never transferred.
 */
class StoreServiceRequestRequest extends FormRequest
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
