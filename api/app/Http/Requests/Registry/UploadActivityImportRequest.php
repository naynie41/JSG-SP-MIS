<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Registry\Enums\Lga;
use App\Domain\Registry\Imports\Adapters\SourceAdapterRegistry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Preview step of the activity-creation wizard's MANDATORY upload for a beneficiary-
 * involving activity (§10). Reaching this endpoint means the activity involves
 * beneficiaries, so a `target_beneficiaries` count and a beneficiary file are BOTH
 * required, and the draft is stamped `involves_beneficiaries = true`. It carries the
 * draft ACTIVITY fields (validated but NOT persisted); validation + the duplicate
 * cascade run in preview before anything is saved. On confirm, the activity is created
 * and the file committed under it, atomically.
 */
class UploadActivityImportRequest extends FormRequest
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
        $sources = app(SourceAdapterRegistry::class)->importableSources();

        return [
            // The draft activity (mirrors StoreActivityRequest; persisted only on confirm).
            'programme_id' => ['required', 'uuid', 'exists:programmes,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            // Involvement is implied by this endpoint; a target is mandatory here.
            'target_beneficiaries' => ['required', 'integer', 'min:1'],
            'lga' => ['nullable', Rule::enum(Lga::class)],
            'ward' => ['nullable', 'string', 'max:100'],
            'location_description' => ['nullable', 'string', 'max:255'],
            'schedule' => ['nullable', 'array'],
            'starts_on' => ['nullable', 'date'],
            'ends_on' => ['nullable', 'date', 'after_or_equal:starts_on'],
            'budget_amount' => ['nullable', 'integer', 'min:0'],
            'funding_source' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::enum(ActivityStatus::class)],

            // The optional-upload payload (required at THIS endpoint — no file → use POST /activities).
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls', 'max:10240'],
            'source' => ['nullable', Rule::in($sources)],
        ];
    }

    /**
     * The validated activity fields to stash until confirm. Reaching this endpoint
     * means the activity involves beneficiaries, so the flag is stamped on here.
     *
     * @return array<string, mixed>
     */
    public function activityDraft(): array
    {
        return [
            ...$this->safe()->except(['file', 'source']),
            'involves_beneficiaries' => true,
        ];
    }
}
