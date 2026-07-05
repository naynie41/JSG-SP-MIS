<?php

declare(strict_types=1);

namespace App\Domain\Registry\Jobs;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Enums\ImportRowResolution;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use App\Domain\Registry\Services\BeneficiaryRegistrar;
use App\Domain\Registry\Services\HouseholdIngestionService;
use App\Domain\Registry\Services\ServeRequestService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

/**
 * Commits the confirmed preview (PRD FR-REG-02, FR-DUP-05). Per row, honouring the
 * officer's resolution: NEW → create a beneficiary (owned by the importing MDA,
 * full provenance, forming/joining a household on a source reference); LINK → raise
 * a request-to-serve the matched existing beneficiary (no new record, ownership
 * unchanged); SKIP (or an unresolved flagged row) → nothing. Non-flagged rows
 * default to NEW. Invalid rows are left in place and reported.
 *
 * Idempotent + retry-safe: a created row is stamped with `beneficiary_id` and
 * serve requests dedupe on the pending state, so re-running never double-inserts.
 * The payload carries only IDs (batch id + confirming user id).
 */
class CommitImportBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly string $batchId,
        public readonly ?string $actorId = null,
    ) {}

    public function handle(BeneficiaryRegistrar $registrar, HouseholdIngestionService $households, ServeRequestService $serveRequests): void
    {
        $batch = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->find($this->batchId);

        if ($batch === null || $batch->status === ImportStatus::Completed) {
            return; // already committed — idempotent no-op
        }
        if (! in_array($batch->status, [ImportStatus::PreviewReady, ImportStatus::Committing], true)) {
            return; // only a confirmed preview may be committed
        }

        // Attribute the audit trail to the confirming user (the queue worker has
        // no authenticated user of its own).
        $actor = $this->actorId !== null ? User::query()->find($this->actorId) : null;
        if ($actor !== null) {
            Auth::setUser($actor);
        }

        $batch->update(['status' => ImportStatus::Committing]);

        // NB: we don't pre-filter on is_valid — an exact-duplicate row is "invalid"
        // for creation yet its whole point may be a LINK/serve. Validity is enforced
        // per-branch instead: only NEW requires a valid row.
        $batch->rows()
            ->whereNull('beneficiary_id')
            ->orderBy('row_number')
            ->chunkById(200, function ($rows) use ($batch, $registrar, $households, $serveRequests, $actor): void {
                foreach ($rows as $row) {
                    /** @var ImportRow $row */
                    $resolution = $this->effectiveResolution($row);

                    if ($resolution === ImportRowResolution::Link) {
                        // Serve the matched existing beneficiary — allowed even for a
                        // duplicate-flagged (is_valid=false) row; nothing is created.
                        $this->serve($row, $batch, $serveRequests, $actor);

                        continue;
                    }
                    if ($resolution !== ImportRowResolution::New || ! $row->is_valid) {
                        continue; // skip / unresolved flagged / invalid — create nothing
                    }

                    try {
                        DB::transaction(function () use ($row, $batch, $registrar, $households): void {
                            // Same provenance-stamping choke-point as every other
                            // inbound channel (Auditable → beneficiary.created).
                            // The source record id doubles as the idempotency key
                            // so re-importing the same export doesn't duplicate.
                            $beneficiary = $registrar->register(
                                $row->payload,
                                $batch->owner_mda_id,
                                $batch->source,
                                $row->original_record_id,
                                $batch->id,
                                $row->original_record_id,
                            );

                            $row->update(['beneficiary_id' => $beneficiary->id]);

                            // Form/join the household when the source grouped rows (§9).
                            if ($row->household_ref !== null) {
                                $households->attach(
                                    $batch->owner_mda_id,
                                    $batch->source,
                                    $batch->id,
                                    $row->household_ref,
                                    $beneficiary,
                                    $row->household_role,
                                    $row->household_head,
                                );
                            }
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
        // Every row ends up committed (new), served (link), or skipped (everything
        // else: explicit skip, unresolved flagged, or invalid).
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
     * A row's effective decision: an explicit resolution, else NEW for a
     * non-flagged row, else null (flagged + unresolved → create nothing).
     */
    private function effectiveResolution(ImportRow $row): ?ImportRowResolution
    {
        if ($row->resolution !== null) {
            return ImportRowResolution::tryFrom($row->resolution);
        }

        return in_array($row->match_band, ['exact', 'probable'], true) ? null : ImportRowResolution::New;
    }

    /** Raise a request-to-serve for a LINK row — never creates a beneficiary. */
    private function serve(ImportRow $row, ImportBatch $batch, ServeRequestService $serveRequests, ?User $actor): void
    {
        if ($row->resolved_beneficiary_id === null) {
            return;
        }

        $beneficiary = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->find($row->resolved_beneficiary_id);
        if ($beneficiary === null) {
            return;
        }

        $serveRequests->request($beneficiary, $batch->owner_mda_id, $actor, $row->resolution_note, $row->id);
    }

    public function failed(Throwable $e): void
    {
        ImportBatch::query()->withoutGlobalScope(MdaScope::class)->where('id', $this->batchId)->update([
            'status' => ImportStatus::Failed->value,
            'error' => Str::limit($e->getMessage(), 500),
        ]);
    }
}
