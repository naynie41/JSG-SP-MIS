<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Jobs;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Imports\BenefitDeliveryRowValidator;
use App\Domain\Benefit\Models\BenefitImportBatch;
use App\Domain\Benefit\Models\BenefitImportRow;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Imports\SpreadsheetReader;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Parses + validates an uploaded benefit-delivery list on the queue, producing the
 * preview (PRD FR-BEN-02, §8.3). Row-level results are staged in
 * `benefit_import_rows`; NOTHING is committed to the ledger. Idempotent (re-parsing
 * replaces the staged rows) and retry-safe; the payload carries only the batch id.
 */
class ParseBenefitImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public readonly string $batchId) {}

    public function handle(SpreadsheetReader $reader, BenefitDeliveryRowValidator $validator): void
    {
        $batch = BenefitImportBatch::query()->withoutGlobalScope(MdaScope::class)->find($this->batchId);

        if ($batch === null || in_array($batch->status, [ImportStatus::Committing, ImportStatus::Completed], true)) {
            return;
        }

        $batch->update(['status' => ImportStatus::Processing, 'error' => null]);

        try {
            $path = Storage::disk('local')->path($batch->stored_path);
            $extension = pathinfo($batch->stored_path, PATHINFO_EXTENSION);
            $parsed = $reader->read($path, $extension);
        } catch (Throwable $e) {
            $batch->update(['status' => ImportStatus::Failed, 'error' => 'Could not read the file: '.Str::limit($e->getMessage(), 300)]);

            return;
        }

        $programme = Programme::query()->withoutGlobalScope(MdaScope::class)->findOrFail($batch->programme_id);

        DB::transaction(function () use ($batch, $parsed, $validator, $programme): void {
            $batch->rows()->delete(); // idempotent re-parse

            $total = 0;
            $valid = 0;

            foreach ($parsed['rows'] as $row) {
                $total++;
                $result = $validator->validate($row['values'], $programme);
                $valid += $result['is_valid'] ? 1 : 0;

                BenefitImportRow::create([
                    'benefit_import_batch_id' => $batch->id,
                    'row_number' => $row['number'],
                    'is_valid' => $result['is_valid'],
                    'errors' => $result['errors'] === [] ? null : $result['errors'],
                    'eligibility_flagged' => $result['eligibility_flagged'],
                    'resolved_beneficiary_id' => $result['resolved_beneficiary_id'],
                    'payload' => $result['payload'],
                ]);
            }

            $batch->update([
                'status' => ImportStatus::PreviewReady,
                'total_rows' => $total,
                'valid_rows' => $valid,
                'invalid_rows' => $total - $valid,
                'committed_rows' => 0,
            ]);
        });
    }

    public function failed(Throwable $e): void
    {
        BenefitImportBatch::query()->withoutGlobalScope(MdaScope::class)->where('id', $this->batchId)->update([
            'status' => ImportStatus::Failed->value,
            'error' => Str::limit($e->getMessage(), 500),
        ]);
    }
}
