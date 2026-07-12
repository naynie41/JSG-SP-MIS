<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Registry\Enums\BeneficiaryStatus;
use App\Domain\Registry\Enums\RegistrationSource;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validates a beneficiary registry export request (FR-REG-04 + FR-RPT-03). The
 * filter/search params mirror the list endpoint exactly; only the output format is
 * export-specific. Authorization is enforced by the `beneficiary.export` route
 * middleware — this request just shapes the input.
 */
class ExportBeneficiariesRequest extends FormRequest
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
            'format' => ['required', Rule::in(['csv', 'excel'])],
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
            'filter.lga' => ['sometimes', 'nullable', 'string', 'max:255'],
            'filter.ward' => ['sometimes', 'nullable', 'string', 'max:255'],
            'filter.status' => ['sometimes', 'nullable', Rule::enum(BeneficiaryStatus::class)],
            'filter.source' => ['sometimes', 'nullable', Rule::enum(RegistrationSource::class)],
            'filter.batch' => ['sometimes', 'nullable', 'uuid'],
        ];
    }
}
