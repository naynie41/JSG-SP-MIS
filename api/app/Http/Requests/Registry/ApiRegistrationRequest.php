<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Registry\Enums\HouseholdRole;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Support\BeneficiaryRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Inbound REST registration intake (PRD FR-REG-02, source = "api") — one of the
 * source-ingestion doors, not a manual-entry form: it requires the caller's own
 * record id so every ingested record stays traceable to origin.
 *
 * Uses the shared {@see BeneficiaryRules} so an API submission is validated
 * identically to every other source.
 */
class ApiRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Normalise identifiers before validation (mirrors every other source path), and
     * accept the file pipeline's canonical `household_ref` name alongside the
     * `household_id` this endpoint originally shipped with. One concept, one meaning:
     * an integrator reading docs/registry-intake.md should not have to discover that
     * the same field is named differently depending on which door they use.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nin' => $this->filled('nin') ? Beneficiary::normalizeDigits((string) $this->input('nin')) : null,
            'bvn' => $this->filled('bvn') ? Beneficiary::normalizeDigits((string) $this->input('bvn')) : null,
            // Phone is NOT normalized here: the caller's written form is what gets
            // stored, and the comparable form is derived on the record (CLAUDE.md §11).
        ]);

        if (! $this->filled('household_id') && $this->filled('household_ref')) {
            $this->merge(['household_id' => $this->input('household_ref')]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            ...BeneficiaryRules::forRegistration(),
            'original_record_id' => ['required', 'string', 'max:255'],
            // Optional explicit idempotency key; defaults to original_record_id.
            'idempotency_key' => ['nullable', 'string', 'max:255'],
            // Optional household grouping (§9): the source's household key + role.
            // `household_ref` is the canonical name and is folded into `household_id`
            // above; both are accepted so existing callers keep working.
            'household_id' => ['nullable', 'string', 'max:255'],
            'household_ref' => ['nullable', 'string', 'max:255'],
            'household_role' => ['nullable', Rule::enum(HouseholdRole::class)],
            'household_head' => ['nullable', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return BeneficiaryRules::messages();
    }
}
