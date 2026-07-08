<?php

declare(strict_types=1);

namespace App\Http\Requests\Reporting;

use App\Domain\Reporting\Export\ReportFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request to generate a standard report in a format (PRD FR-RPT-03).
 */
class GenerateReportRequest extends FormRequest
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
            'report_key' => ['required', 'string', 'max:64'],
            'format' => ['required', Rule::enum(ReportFormat::class)],
            'params' => ['nullable', 'array'],
        ];
    }
}
