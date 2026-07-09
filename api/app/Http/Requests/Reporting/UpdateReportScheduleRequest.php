<?php

declare(strict_types=1);

namespace App\Http\Requests\Reporting;

use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Models\ReportSchedule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Edit / pause / resume a report schedule (PRD FR-RPT-04). All fields optional; only
 * the mutable ones are applied. Recipients are re-validated against the captured scope.
 */
class UpdateReportScheduleRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:120'],
            'format' => ['sometimes', Rule::enum(ReportFormat::class)],
            'frequency' => ['sometimes', Rule::in(ReportSchedule::FREQUENCIES)],
            'delivery' => ['sometimes', Rule::in([ReportSchedule::DELIVERY_LINK, ReportSchedule::DELIVERY_ATTACHMENT])],
            'status' => ['sometimes', Rule::in([ReportSchedule::STATUS_ACTIVE, ReportSchedule::STATUS_PAUSED])],
            'recipient_user_ids' => ['sometimes', 'array', 'min:1'],
            'recipient_user_ids.*' => ['string', 'uuid'],
        ];
    }
}
