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
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
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
 * activity (FR-OWN-06), intervention DEFERRED until owner approval (FR-BEN-06); SKIP
 * (or an unresolved flagged/invalid row) → nothing.
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
        if ($batch->activity_id === null) {
            // Activity-first at commit: a batch cannot produce interventions unbound.
            throw new RuntimeException('The import batch is not bound to an activity.');
        }

        // The bound activity + its catalog programme drive whether the intervention is
        // recorded per beneficiary or per household.
        $activity = Activity::query()->withoutGlobalScope(MdaScope::class)->find($batch->activity_id);
        $programme = $activity !== null
            ? Programme::query()->find($activity->programme_id)
            : null;

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

                    if ($resolution === ImportRowResolution::Link) {
                        // Serve the matched existing beneficiary — a PENDING Service
                        // Request under the activity; nothing is created, no intervention.
                        $this->serve($row, $batch, $actor);

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

        $batch->update([
            'status' => ImportStatus::Completed,
            'committed_rows' => $committed,
            'served_rows' => $served,
            'skipped_rows' => max(0, $total - $committed - $served),
        ]);
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
     * Record the intervention under the batch's activity (§9/§10, FR-REG-10): an
     * enrollment of the just-imported target into the activity's programme. Individual
     * programmes enroll the beneficiary; household programmes enroll the formed
     * household. Best-effort — a duplicate/ineligible/type-mismatched target records no
     * enrollment and never blocks the commit. Needs a confirming actor for attribution.
     */
    private function recordIntervention(?Programme $programme, ?Activity $activity, Beneficiary $beneficiary, ?Household $household, ?User $actor): void
    {
        if ($programme === null || $activity === null || $actor === null) {
            return;
        }

        $target = $programme->type === ProgrammeType::Household ? $household : $beneficiary;
        if ($target === null) {
            return; // household programme but this row formed no household
        }

        $this->enrollments->enroll($programme, $target, $activity->id, $actor);
    }

    /**
     * Raise a PENDING Service Request for a LINK row, attached to the batch's activity
     * (§10). Never creates a beneficiary; the intervention is deferred until the owner
     * MDA approves.
     */
    private function serve(ImportRow $row, ImportBatch $batch, ?User $actor): void
    {
        if ($row->resolved_beneficiary_id === null) {
            return;
        }

        $beneficiary = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->find($row->resolved_beneficiary_id);
        if ($beneficiary === null) {
            return;
        }

        $this->serviceRequests->request($beneficiary, $batch->owner_mda_id, $actor, $row->resolution_note, $row->id, $batch->activity_id);
    }
}
