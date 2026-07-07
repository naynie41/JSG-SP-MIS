<?php

declare(strict_types=1);

namespace App\Http\Requests\Grievance;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Set the SLA window (hours) for a grievance category (PRD FR-GRM-03).
 */
class UpdateGrievanceSlaRequest extends FormRequest
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
            'hours' => ['required', 'integer', 'min:1', 'max:8760'],
        ];
    }
}
