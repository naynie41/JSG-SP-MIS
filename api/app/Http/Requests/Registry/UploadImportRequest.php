<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\Activity;
use App\Domain\Registry\Imports\Adapters\SourceAdapterRegistry;
use Closure;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Upload a file for bulk beneficiary import (PRD FR-REG-02). Accepts plain
 * Excel/CSV as well as Kobo/ODK exports; the optional `source` selects the
 * ingestion adapter (and thus the stamped provenance). When omitted, the source
 * is inferred from the file extension (excel/csv).
 *
 * PROGRAMME-first (revises the activity-first rule in PRD §9 / CLAUDE.md §9): every
 * upload must name a catalog programme, and MAY additionally name an `activity_id` the
 * caller's MDA owns. `programme_id` is required only when no activity is given — an
 * activity already names its programme, so the two together are one fact, not two.
 *
 * Registering people under a catalog programme is a complete act — the enrollment records
 * that they are on that programme. An activity adds *which MDA-run instance* delivered to
 * them, which an intake frequently does not know yet; requiring one made officers invent
 * placeholder activities, and a placeholder is a worse record than an honest absence.
 *
 * When both are given they must agree: an activity belongs to exactly one programme, and
 * accepting a contradiction would leave the batch's own `programme_id` disagreeing with
 * the programme its enrollments actually land in.
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
            // One of the two is mandatory. `programme_id` is required only when no
            // activity is given, because an activity already names its programme
            // unambiguously — demanding both would be asking the caller to repeat a fact
            // the server can read, and gives two places for it to be wrong.
            // `nullable` first so `uuid`/`exists` are skipped when the field is absent —
            // without it an activity-only upload fails on a uuid check against nothing.
            // `required_without` is an implicit rule and still fires, so "neither given"
            // is refused.
            'programme_id' => ['nullable', 'required_without:activity_id', 'uuid', 'exists:programmes,id'],
            'activity_id' => ['nullable', 'uuid', $this->usableActivityRule()],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $activityId = $this->input('activity_id');
            $programmeId = $this->input('programme_id');

            // Only a CONTRADICTION is an error. When just an activity is given there is
            // nothing to contradict — its programme is simply the answer — so comparing
            // against the absent field would reject every activity-only upload.
            if ($validator->errors()->isNotEmpty()) {
                return;
            }
            if (! is_string($activityId) || $activityId === '' || ! is_string($programmeId) || $programmeId === '') {
                return;
            }

            $activity = Activity::query()->withoutGlobalScope(MdaScope::class)->find($activityId);
            if ($activity !== null && $activity->programme_id !== $programmeId) {
                $validator->errors()->add(
                    'activity_id',
                    'That activity runs a different programme from the one selected.',
                );
            }
        });
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
