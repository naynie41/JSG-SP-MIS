<?php

declare(strict_types=1);

namespace App\Http\Requests\Reporting;

use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Models\ReportSchedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Create a report schedule (PRD FR-RPT-04). Exactly one of `report_key` (standard) or
 * `report_definition_id` (saved ad-hoc) identifies what to generate; recipient scope
 * is validated in the service.
 */
class StoreReportScheduleRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:120'],
            'report_key' => ['nullable', 'string', 'max:64'],
            'report_definition_id' => ['nullable', 'string', 'uuid'],
            'format' => ['required', Rule::enum(ReportFormat::class)],
            'frequency' => ['required', Rule::in(ReportSchedule::FREQUENCIES)],
            'delivery' => ['nullable', Rule::in([ReportSchedule::DELIVERY_LINK, ReportSchedule::DELIVERY_ATTACHMENT])],
            'recipient_user_ids' => ['required', 'array', 'min:1'],
            'recipient_user_ids.*' => ['string', 'uuid'],
        ];
    }
}
