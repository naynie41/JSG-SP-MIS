<?php

declare(strict_types=1);

namespace App\Http\Requests\Reference;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Wards are always requested for ONE LGA — that is what the cascading selector asks
 * for, and `lga_id` is required rather than optional on purpose: an unfiltered call
 * would return every ward in the state, which no screen wants and which would quietly
 * become the expensive default.
 */
class ListWardsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // route-level authentication; reference data carries no PII
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'lga_id' => ['required', 'uuid', 'exists:lgas,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'lga_id.required' => 'lga_id is required — wards are listed one LGA at a time.',
            'lga_id.exists' => 'No such LGA. Reference data may not be loaded yet.',
        ];
    }
}
