<?php

declare(strict_types=1);

namespace App\Http\Requests\Reporting;

use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Segments\SegmentDefinition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * A composed segment query (FR-RPT-03).
 *
 * Shape only. WHICH dimensions exist, which values they accept and which operators
 * they support are decided by {@see SegmentDefinition},
 * against the live dimension catalogue — putting that list here would freeze it, and
 * the whole point is that a new segmentable schema field becomes filterable without a
 * code change.
 */
class SegmentReportRequest extends FormRequest
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
            'filters' => ['sometimes', 'array'],
            'filters.*.op' => ['sometimes', 'nullable', 'string', Rule::in(['in', 'between'])],
            'filters.*.values' => ['sometimes', 'array', 'max:200'],
            'filters.*.values.*' => ['nullable', 'string', 'max:255'],
            'breakdown' => ['sometimes', 'nullable', 'string', 'max:64'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'format' => ['sometimes', Rule::enum(ReportFormat::class)],
        ];
    }
}
