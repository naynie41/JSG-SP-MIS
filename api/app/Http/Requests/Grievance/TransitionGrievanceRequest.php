<?php

declare(strict_types=1);

namespace App\Http\Requests\Grievance;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Body for a grievance transition (PRD FR-GRM-02). Resolution notes are required
 * when resolving; optional when closing.
 */
class TransitionGrievanceRequest extends FormRequest
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
        $isResolve = $this->routeIs('grievances.resolve');

        return [
            'resolution_notes' => [$isResolve ? 'required' : 'nullable', 'string', 'max:5000'],
        ];
    }
}
