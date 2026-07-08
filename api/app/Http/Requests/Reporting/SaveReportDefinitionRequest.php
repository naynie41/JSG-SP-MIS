<?php

declare(strict_types=1);

namespace App\Http\Requests\Reporting;

/**
 * Save an ad-hoc report definition for reuse (PRD FR-RPT-03).
 */
class SaveReportDefinitionRequest extends AdHocReportRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'name' => ['required', 'string', 'max:120'],
        ];
    }
}
