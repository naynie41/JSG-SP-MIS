<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\Activity;
use App\Domain\Registry\Imports\Adapters\SourceAdapterRegistry;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Upload a file for bulk beneficiary import (PRD FR-REG-02). Accepts plain
 * Excel/CSV as well as Kobo/ODK exports; the optional `source` selects the
 * ingestion adapter (and thus the stamped provenance). When omitted, the source
 * is inferred from the file extension (excel/csv).
 *
 * Activity-first (PRD §9, FR-REG-10): a required, valid `activity_id` that the
 * caller's MDA owns must accompany every upload — the resulting intervention is
 * recorded under it. An upload with no/invalid activity, or one the caller's MDA
 * cannot use, is refused here.
 */
class UploadImportRequest extends FormRequest
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
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls', 'max:10240'],
            'source' => ['nullable', Rule::in($sources)],
            'activity_id' => ['required', 'uuid', $this->usableActivityRule()],
        ];
    }

    /**
     * The activity must exist and be owned by the caller's own MDA — the importing
     * MDA. Resolved without the global MDA scope so a cross-MDA activity fails as
     * "not usable" (a clear 422) rather than a bare "not found".
     */
    private function usableActivityRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            $mdaId = $this->user()?->mda_id;

            $activity = Activity::query()
                ->withoutGlobalScope(MdaScope::class)
                ->whereKey($value)
                ->first();

            if ($activity === null) {
                $fail('The selected activity does not exist.');

                return;
            }

            if ($mdaId === null || $activity->owner_mda_id !== $mdaId) {
                $fail('Your MDA cannot upload beneficiaries to this activity.');
            }
        };
    }
}
