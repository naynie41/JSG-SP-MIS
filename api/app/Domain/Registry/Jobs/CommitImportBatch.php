<?php

declare(strict_types=1);

namespace App\Domain\Registry\Jobs;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
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
 * Commits the valid rows of a confirmed preview as beneficiaries owned by the
 * importing MDA, with full provenance (PRD FR-REG-02). Invalid rows are left in
 * place and reported — never silently dropped.
 *
 * Idempotent + retry-safe: a committed row is stamped with `beneficiary_id`, so
 * re-running (a retry, or a second confirm) never double-inserts. The payload
 * carries only IDs (batch id + confirming user id).
 */
class CommitImportBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly string $batchId,
        public readonly ?string $actorId = null,
    ) {}

    public function handle(): void
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

        $committed = $batch->committed_rows;

        $batch->rows()
            ->where('is_valid', true)
            ->whereNull('beneficiary_id')
            ->orderBy('row_number')
            ->chunkById(200, function ($rows) use ($batch, &$committed): void {
                foreach ($rows as $row) {
                    /** @var ImportRow $row */
                    try {
                        DB::transaction(function () use ($row, $batch): void {
                            $beneficiary = new Beneficiary;
                            $beneficiary->fill($row->payload);
                            $beneficiary->owner_mda_id = $batch->owner_mda_id;
                            $beneficiary->registration_source = $batch->source;
                            $beneficiary->import_batch_id = $batch->id;
                            $beneficiary->original_record_id = $row->original_record_id;
                            $beneficiary->save(); // Auditable → beneficiary.created

                            $row->update(['beneficiary_id' => $beneficiary->id]);
                        });
                        $committed++;
                    } catch (UniqueConstraintViolationException) {
                        // Raced/duplicate identifier — flag the row, don't crash.
                        $row->update([
                            'is_valid' => false,
                            'errors' => [['field' => 'nin', 'message' => 'A beneficiary with this identifier already exists.']],
                        ]);
                        $batch->decrement('valid_rows');
                        $batch->increment('invalid_rows');
                    }
                }
            });

        $batch->update(['status' => ImportStatus::Completed, 'committed_rows' => $committed]);
    }

    public function failed(Throwable $e): void
    {
        ImportBatch::query()->withoutGlobalScope(MdaScope::class)->where('id', $this->batchId)->update([
            'status' => ImportStatus::Failed->value,
            'error' => Str::limit($e->getMessage(), 500),
        ]);
    }
}
