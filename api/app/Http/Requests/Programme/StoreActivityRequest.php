<?php

declare(strict_types=1);

namespace App\Http\Requests\Programme;

use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Registry\Enums\Lga;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Create an activity under a programme (PRD FR-PRG-02, §10). This is the NO-file path:
 * it creates an activity that does NOT involve beneficiaries. A beneficiary-involving
 * activity (`involves_beneficiaries = true`) requires a mandatory upload and is created
 * through the activity-import wizard ({@see UploadActivityImportRequest} → confirm), so
 * a target count and a file are BOTH prohibited here. Ownership is enforced in the
 * controller/policy; monetary amounts are integer minor units (kobo, NGN).
 */
class StoreActivityRequest extends FormRequest
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
            'programme_id' => ['required', 'uuid', 'exists:programmes,id'],
            'involves_beneficiaries' => ['required', 'boolean'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            // No target and no file on this path — a target/upload belongs to the
            // involves=true wizard flow (rejected here per §10).
            'target_beneficiaries' => ['prohibited'],
            'file' => ['prohibited'],
            'lga' => ['nullable', Rule::enum(Lga::class)],
            'ward' => ['nullable', 'string', 'max:100'],
            'location_description' => ['nullable', 'string', 'max:255'],
            'schedule' => ['nullable', 'array'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'budget_amount' => ['nullable', 'integer', 'min:0'],
            'funding_source' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::enum(ActivityStatus::class)],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            // A beneficiary-involving activity MUST come through the upload wizard,
            // which requires a mandatory beneficiary file (§10). This metadata-only
            // endpoint cannot create one.
            if ($validator->errors()->isEmpty() && $this->boolean('involves_beneficiaries')) {
                $validator->errors()->add(
                    'involves_beneficiaries',
                    'A beneficiary-involving activity requires a beneficiary file — create it through the upload step.',
                );
            }
        });
    }
}
