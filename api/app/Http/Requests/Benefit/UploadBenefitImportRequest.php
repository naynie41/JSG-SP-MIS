<?php

declare(strict_types=1);

namespace App\Http\Requests\Benefit;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Upload a bulk benefit-delivery list keyed to an activity (PRD FR-BEN-02, §8.3).
 * Rows reference EXISTING beneficiaries — this never registers beneficiaries.
 */
class UploadBenefitImportRequest extends FormRequest
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
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls', 'max:10240'],
            'activity_id' => ['required', 'uuid', 'exists:activities,id'],
        ];
    }
}
