<?php

declare(strict_types=1);

namespace App\Http\Requests\Grievance;

use App\Domain\Grievance\Enums\GrievanceCategory;
use App\Domain\Grievance\Enums\GrievanceChannel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Staff logs a grievance on behalf of a beneficiary (PRD FR-GRM-01). The
 * beneficiary link is optional; category + channel come from fixed taxonomies.
 */
class StoreGrievanceRequest extends FormRequest
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
            'category' => ['required', Rule::enum(GrievanceCategory::class)],
            'channel' => ['required', Rule::enum(GrievanceChannel::class)],
            'beneficiary_id' => ['nullable', 'uuid'],
            'description' => ['required', 'string', 'max:5000'],
        ];
    }
}
