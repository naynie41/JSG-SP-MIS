<?php

declare(strict_types=1);

namespace App\Http\Requests\Reporting;

use App\Domain\Reporting\Export\ReportFormat;
use Illuminate\Validation\Rule;

/**
 * Export an ad-hoc report — a definition plus the target format (PRD FR-RPT-03).
 */
class ExportAdHocRequest extends AdHocReportRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'format' => ['required', Rule::enum(ReportFormat::class)],
        ];
    }
}
