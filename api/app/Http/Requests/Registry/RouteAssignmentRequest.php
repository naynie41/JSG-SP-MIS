<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Confirm an auto-route assignment (PRD FR-OWN-04): enrol the beneficiary into a
 * chosen suggested programme. Explicit + audited — nothing is assigned silently.
 */
class RouteAssignmentRequest extends FormRequest
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
            'programme_id' => ['required', 'uuid', 'exists:programmes,id'],
            'activity_id' => ['nullable', 'uuid', 'exists:activities,id'],
            'need' => ['nullable', 'string', 'max:255'],
        ];
    }
}
