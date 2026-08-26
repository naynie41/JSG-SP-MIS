<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Enums\ProgrammeType;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Services\EnrollmentService;
use App\Domain\Registry\Enums\ImportRowResolution;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Events\ImportBatchCompleted;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * The single commit engine for a confirmed import preview (PRD FR-REG-02, FR-DUP-05,
 * §10). Per row, honouring the officer's resolution: NEW → create a beneficiary
 * (owned by the importing MDA, FR-OWN-01) + record the intervention under the batch's
 * activity (activity-first); LINK → raise a PENDING Service Request attached to that
 * activity (FR-OWN-06), intervention DEFERRED until owner approval (FR-BEN-06); OWN →
 * the match is this MDA's OWN beneficiary, so no second record and no request — the
 * existing person receives a new intervention; SKIP (or an unresolved flagged/invalid
 * row) → nothing. LINK and OWN are chosen by OWNERSHIP of the matched record, not by
 * the stored label (see resolveAgainstExisting).
 *
 * Reused verbatim by BOTH the async {@see CommitImportBatch} job (standalone Import
 * Center) and the activity-creation wizard's atomic confirm — no parallel logic. The
 * batch MUST be bound to an activity before committing (activity-first at commit).
 * Idempotent + retry-safe: a created row is stamped with `beneficiary_id` and service
 * requests dedupe on the pending state, so re-running never double-inserts.
 */
class ImportCommitter
{
    public function __construct(
        private readonly BeneficiaryRegistrar $registrar,
        private readonly HouseholdIngestionService $households,
        private readonly ServiceRequestService $serviceRequests,
        private readonly EnrollmentService $enrollments,
    ) {}

    public function commit(ImportBatch $batch, ?User $actor): void
    {
        if ($batch->status === ImportStatus::Completed) {
            return; // already committed — idempotent no-op
        }
        if (! in_array($batch->status, [ImportStatus::PreviewReady, ImportStatus::Committing], true)) {
            return; // only a confirmed preview may be committed
        }
        // A batch must name a PROGRAMME; an activity is optional. Registering people
        // under a catalog programme is a complete, meaningful act — the activity adds
        // *which MDA-run instance* delivered to them, which not every intake has yet.
        // Without a programme there is nothing to enroll into, and the upload would be a
        // silent registry-only write dressed up as an intervention.
        if ($batch->activity_id === null && $batch->programme_id === null) {
            throw new RuntimeException('The import batch names neither a programme nor an activity.');
        }

        // The activity, when there is one, decides which MDA-run instance the intervention
        // sits under. The programme decides whether it is recorded per beneficiary or per
        // household, and is taken from the activity when bound so the two cannot disagree.
        $activity = $batch->activity_id === null
            ? null
            : Activity::query()->withoutGlobalScope(MdaScope::class)->find($batch->activity_id);

        $programme = Programme::query()->find($activity->programme_id ?? $batch->programme_id);

        $batch->update(['status' => ImportStatus::Committing]);

        // NB: we don't pre-filter on is_valid — an exact-duplicate row is "invalid"
        // for creation yet its whole point may be a LINK/serve. Validity is enforced
        // per-branch instead: only NEW requires a valid row.
        $batch->rows()
            ->whereNull('beneficiary_id')
            ->orderBy('row_number')
            ->chunkById(200, function ($rows) use ($batch, $activity, $programme, $actor): void {
                foreach ($rows as $row) {
                    /** @var ImportRow $row */
                    $resolution = $this->effectiveResolution($row);

                    if (in_array($resolution, ImportRowResolution::againstExisting(), true)) {
                        // Acts on an EXISTING person. Which act depends on who owns them,
                        // and that is read from the record here rather than trusted from
                        // the stored label — see resolveAgainstExisting().
                        $this->resolveAgainstExisting($row, $batch, $activity, $programme, $actor);

                        continue;
                    }
                    if ($resolution !== ImportRowResolution::New || ! $row->is_valid) {
                        continue; // skip / unresolved flagged / invalid — create nothing
                    }

                    try {
                        DB::transaction(function () use ($row, $batch, $activity, $programme, $actor): void {
                            // Same provenance-stamping choke-point as every other inbound
                            // channel (Auditable → beneficiary.created). The source record
                            // id doubles as the idempotency key.
                            $beneficiary = $this->registrar->register(
                                $row->payload,
                                $batch->owner_mda_id,
                                $batch->source,
                                $row->original_record_id,
                                $batch->id,
                                $row->original_record_id,
                            );

                            $row->update(['beneficiary_id' => $beneficiary->id]);

                            $household = null;
                            if ($row->household_ref !== null) {
                                $household = $this->households->attach(
                                    $batch->owner_mda_id,
                                    $batch->source,
                                    $batch->id,
                                    $row->household_ref,
                                    $beneficiary,
                                    $row->household_role,
                                    $row->household_head,
                                );
                            }

                            $this->recordIntervention($programme, $activity, $beneficiary, $household, $actor);
                        });
                    } catch (UniqueConstraintViolationException) {
                        // Raced/duplicate identifier — flag the row, don't crash.
                        $row->update([
                            'is_valid' => false,
                            'errors' => [['field' => 'nin', 'message' => 'A beneficiary with this identifier already exists.', 'group' => 'duplicate']],
                        ]);
                    }
                }
            });

        // Recompute counters from the final row state (idempotent under resume).
        $total = (int) $batch->rows()->count();
        $committed = (int) $batch->rows()->whereNotNull('beneficiary_id')->count();
        $served = (int) $batch->rows()->where('resolution', ImportRowResolution::Link->value)->count();
        // Rows that matched a person this MDA already owns. Counted apart from `skipped`
        // because an own-match records a delivery on the existing record — reporting it
        // as discarded would tell the officer the opposite of what happened.
        $own = (int) $batch->rows()->where('resolution', ImportRowResolution::Own->value)->count();

        $skipped = max(0, $total - $committed - $served - $own);

        $batch->update([
            'status' => ImportStatus::Completed,
            'committed_rows' => $committed,
            'served_rows' => $served,
            'own_rows' => $own,
            'skipped_rows' => $skipped,
        ]);

        // Tell the uploader how it went. Fired here rather than in CommitImportBatch so
        // it covers BOTH entry points — the Import Center's queued commit and the
        // activity wizard's atomic confirm both land in this method.
        ImportBatchCompleted::dispatch($batch, $committed, $served, $skipped, $own);
    }

    /**
     * A row's effective decision: an explicit resolution, else NEW for a non-flagged
     * row, else null (flagged + unresolved → create nothing).
     */
    private function effectiveResolution(ImportRow $row): ?ImportRowResolution
    {
        if ($row->resolution !== null) {
            return ImportRowResolution::tryFrom($row->resolution);
        }

        return in_array($row->match_band, ['exact', 'probable'], true) ? null : ImportRowResolution::New;
    }

    /**
     * Record the intervention (§9/§10, FR-REG-10): an enrollment of the just-imported
     * target into the batch's programme. Individual programmes enroll the beneficiary;
     * household programmes enroll the formed household. Best-effort — a
     * duplicate/ineligible/type-mismatched target records no enrollment and never blocks
     * the commit. Needs a confirming actor for attribution.
     *
     * The PROGRAMME is required; the activity is not. A programme-only import enrolls
     * with a null activity — the person is on the programme, and which MDA-run activity
     * served them is simply not yet known. `EnrollmentService::enroll()` already takes a
     * nullable activity id, so this is the shape it was built for.
     */
    private function recordIntervention(?Programme $programme, ?Activity $activity, Beneficiary $beneficiary, ?Household $household, ?User $actor): void
    {
        if ($programme === null || $actor === null) {
            return;
        }

        $target = $programme->type === ProgrammeType::Household ? $household : $beneficiary;
        if ($target === null) {
            return; // household programme but this row formed no household
        }

        $this->enrollments->enroll($programme, $target, $activity?->id, $actor);
    }

    /**
     * A row that acts on an EXISTING matched beneficiary. OWNERSHIP decides which act:
     *
     *  - another MDA's record → PENDING Service Request under the batch's activity
     *    (§10, FR-OWN-06); nothing created, intervention deferred until approval;
     *  - this MDA's own record → no request at all (you do not ask permission to serve
     *    your own beneficiary) and a NEW INTERVENTION on the person who is already there.
     *
     * The decision is re-derived from the record instead of trusting `resolution`, for
     * two reasons. It makes "never request-to-serve your own beneficiary" structural
     * rather than a rule every caller has to remember — `ServiceRequestService::request()`
     * throws a DomainException on a self-owned target, which mid-chunk would abort the
     * whole commit. And it heals rows already stored as LINK against a self-owned match,
     * which auto-link could produce before own-matches were modelled.
     *
     * The row's stored resolution is corrected to whichever act ran, so the batch
     * counters and the decision history describe what actually happened.
     */
    private function resolveAgainstExisting(ImportRow $row, ImportBatch $batch, ?Activity $activity, ?Programme $programme, ?User $actor): void
    {
        if ($row->resolved_beneficiary_id === null) {
            return;
        }

        $beneficiary = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->find($row->resolved_beneficiary_id);
        if ($beneficiary === null) {
            return;
        }

        if ($beneficiary->owner_mda_id === $batch->owner_mda_id) {
            $row->update(['resolution' => ImportRowResolution::Own->value]);

            // The existing person, under this batch's programme/activity. No new
            // beneficiary row: `beneficiary_id` stays null so the row is never counted
            // as a creation, and re-running the commit re-enters this branch harmlessly
            // (EnrollmentService already no-ops on a duplicate enrollment).
            $this->recordIntervention($programme, $activity, $beneficiary, $this->householdOf($beneficiary), $actor);

            return;
        }

        $row->update(['resolution' => ImportRowResolution::Link->value]);

        $this->serviceRequests->request($beneficiary, $batch->owner_mda_id, $actor, $row->resolution_note, $row->id, $batch->activity_id);
    }

    /**
     * The household an already-registered beneficiary belongs to, for a household-typed
     * programme. An own-match enrolls the person as they ALREADY are — the re-uploaded
     * row does not re-form or re-shape their household, because the registry copy is the
     * authoritative one and the upload is a delivery record, not a correction.
     */
    private function householdOf(Beneficiary $beneficiary): ?Household
    {
        // HouseholdMembership carries no MDA scope of its own; the household it points
        // at does, hence the explicit bypass on that lookup and not this one.
        $membership = HouseholdMembership::query()
            ->where('beneficiary_id', $beneficiary->id)
            ->whereNull('left_at')
            ->first();

        return $membership === null
            ? null
            : Household::query()->withoutGlobalScope(MdaScope::class)->find($membership->household_id);
    }
}
