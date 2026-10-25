<?php

declare(strict_types=1);

namespace App\Http\Requests\Reporting;

use App\Domain\Reporting\Services\MonthlyProjectService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

/**
 * Request for the monthly project report (FR-RPT-12b).
 *
 * Both fields are optional and default together to the last complete month, so the
 * plain "generate" button needs to send nothing at all.
 *
 * The upper bound is the point of the validation: a month that has not finished yet
 * would produce a report comparing a part-month against a whole one and showing every
 * project as collapsing. `before_or_equal` on the year alone cannot express that, so
 * the check is done in {@see self::withValidator()} where both fields are in hand.
 */
class MonthlyProjectReportRequest extends FormRequest
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
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
        ];
    }

    protected function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            if ($v->errors()->isNotEmpty()) {
                return;
            }

            $year = $this->input('year');
            $month = $this->input('month');

            if ($year === null && $month === null) {
                return;
            }

            if ($year === null || $month === null) {
                $v->errors()->add('month', 'Give both a year and a month, or neither.');

                return;
            }

            [$maxYear, $maxMonth] = MonthlyProjectService::lastCompleteMonth();
            if (((int) $year * 12 + (int) $month) > ($maxYear * 12 + $maxMonth)) {
                $v->errors()->add('month', 'That month has not finished yet. The latest available is '
                    .Carbon::create($maxYear, $maxMonth, 1)->format('F Y').'.');
            }
        });
    }

    /**
     * @return array{int, int} [year, month]
     */
    public function period(): array
    {
        $year = $this->input('year');
        $month = $this->input('month');

        return ($year === null || $month === null)
            ? MonthlyProjectService::lastCompleteMonth()
            : [(int) $year, (int) $month];
    }
}
