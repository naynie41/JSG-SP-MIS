<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Jobs;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Enums\VerificationMethod;
use App\Domain\Benefit\Exceptions\NotEnrolledException;
use App\Domain\Benefit\Exceptions\VerificationUnavailableException;
use App\Domain\Benefit\Models\BenefitImportBatch;
use App\Domain\Benefit\Models\BenefitImportRow;
use App\Domain\Benefit\Services\BenefitRecorder;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

/**
 * Commits a confirmed benefit-delivery preview to the ledger (PRD FR-BEN-01/02,
 * §8.3) by REUSING the manual recording logic (BenefitRecorder) per valid row —
 * so the delivering MDA is the importer and the same enrollment/verification rules
 * apply. Idempotent + retry-safe: each committed row is stamped with `benefit_id`,
 * so re-running never double-records. Invalid rows are left in place and reported.
 */
class CommitBenefitImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly string $batchId,
        public readonly ?string $actorId = null,
    ) {}

    public function handle(BenefitRecorder $recorder): void
    {
        $batch = BenefitImportBatch::query()->withoutGlobalScope(MdaScope::class)->find($this->batchId);

        if ($batch === null || $batch->status === ImportStatus::Completed) {
            return; // already committed — idempotent no-op
        }
        if (! in_array($batch->status, [ImportStatus::PreviewReady, ImportStatus::Committing], true)) {
            return;
        }

        $actor = $this->actorId !== null ? User::query()->find($this->actorId) : null;
        if ($actor !== null) {
            Auth::setUser($actor);
        }

        $batch->update(['status' => ImportStatus::Committing]);

        $programme = Programme::query()->withoutGlobalScope(MdaScope::class)->findOrFail($batch->programme_id);

        $batch->rows()
            ->where('is_valid', true)
            ->whereNull('benefit_id') // skip already-committed rows (idempotency)
            ->orderBy('row_number')
            ->chunkById(200, function ($rows) use ($batch, $programme, $recorder, $actor): void {
                foreach ($rows as $row) {
                    /** @var BenefitImportRow $row */
                    $this->commitRow($row, $batch, $programme, $recorder, $actor);
                }
            });

        $total = (int) $batch->rows()->count();
        $committed = (int) $batch->rows()->whereNotNull('benefit_id')->count();
        $valid = (int) $batch->rows()->where('is_valid', true)->count();

        $batch->update([
            'status' => ImportStatus::Completed,
            'total_rows' => $total,
            'committed_rows' => $committed,
            'valid_rows' => $valid,
            'invalid_rows' => $total - $valid,
        ]);
    }

    private function commitRow(BenefitImportRow $row, BenefitImportBatch $batch, Programme $programme, BenefitRecorder $recorder, ?User $actor): void
    {
        $beneficiary = $row->resolved_beneficiary_id !== null
            ? Beneficiary::query()->withoutGlobalScope(MdaScope::class)->find($row->resolved_beneficiary_id)
            : null;

        if ($beneficiary === null || $actor === null) {
            $row->update(['is_valid' => false, 'errors' => [['field' => 'beneficiary', 'message' => 'The beneficiary could not be resolved at commit time.']]]);

            return;
        }

        $fields = $row->payload;
        $fields['verification_method'] = VerificationMethod::tryFrom($fields['verification_method'] ?? 'none') ?? VerificationMethod::None;

        try {
            $benefit = $recorder->record($beneficiary, $programme, $batch->activity_id, $actor, $fields);
            $row->update(['benefit_id' => $benefit->id]);
        } catch (NotEnrolledException|VerificationUnavailableException $e) {
            // State changed since preview — report the row, don't crash the batch.
            $row->update(['is_valid' => false, 'errors' => [['field' => 'commit', 'message' => $e->getMessage()]]]);
        }
    }

    public function failed(Throwable $e): void
    {
        BenefitImportBatch::query()->withoutGlobalScope(MdaScope::class)->where('id', $this->batchId)->update([
            'status' => ImportStatus::Failed->value,
            'error' => Str::limit($e->getMessage(), 500),
        ]);
    }
}
