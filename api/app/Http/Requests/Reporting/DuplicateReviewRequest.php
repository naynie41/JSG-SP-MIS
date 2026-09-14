<?php

declare(strict_types=1);

namespace App\Http\Requests\Reporting;

use App\Domain\Reporting\DuplicateReview\DuplicateReviewFilter;
use App\Domain\Reporting\Export\ReportFormat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Narrowing for the duplicate review report, and the export format when exporting. */
class DuplicateReviewRequest extends FormRequest
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
            'date_from' => ['sometimes', 'nullable', 'date_format:Y-m-d'],
            'date_to' => ['sometimes', 'nullable', 'date_format:Y-m-d', 'after_or_equal:date_from'],
            'band' => ['sometimes', 'nullable', Rule::in(DuplicateReviewFilter::bands())],
            'format' => ['sometimes', Rule::enum(ReportFormat::class)],
        ];
    }
}
