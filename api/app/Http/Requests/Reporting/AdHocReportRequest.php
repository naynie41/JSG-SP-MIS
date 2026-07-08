<?php

declare(strict_types=1);

namespace App\Http\Requests\Reporting;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Shape validation for an ad-hoc report definition (PRD FR-RPT-03). Whitelist
 * validation (that the dataset/columns/filters are allowed and within scope) happens
 * in the builder, which returns a 422 with a specific message.
 */
class AdHocReportRequest extends FormRequest
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
            'dataset' => ['required', 'string', 'max:64'],
            'group_by' => ['nullable', 'array'],
            'group_by.*' => ['string', 'max:64'],
            'measures' => ['required', 'array', 'min:1'],
            'measures.*' => ['string', 'max:64'],
            'filters' => ['nullable', 'array'],
        ];
    }
}
