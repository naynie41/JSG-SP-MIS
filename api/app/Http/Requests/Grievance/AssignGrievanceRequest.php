<?php

declare(strict_types=1);

namespace App\Http\Requests\Grievance;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Assign a grievance to a handling-MDA user (PRD FR-GRM-02).
 */
class AssignGrievanceRequest extends FormRequest
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
            'assignee_user_id' => ['required', 'uuid', 'exists:users,id'],
        ];
    }
}
