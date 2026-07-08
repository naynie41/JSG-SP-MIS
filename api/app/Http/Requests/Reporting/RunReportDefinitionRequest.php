<?php

declare(strict_types=1);

namespace App\Http\Requests\Reporting;

use App\Domain\Reporting\Export\ReportFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Generate an export from a saved ad-hoc definition (PRD FR-RPT-03).
 */
class RunReportDefinitionRequest extends FormRequest
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
            'format' => ['required', Rule::enum(ReportFormat::class)],
        ];
    }
}
