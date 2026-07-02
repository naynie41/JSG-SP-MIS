<?php

declare(strict_types=1);

namespace App\Domain\Registry\Jobs;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Imports\ImportRowValidator;
use App\Domain\Registry\Imports\SpreadsheetReader;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
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
 * Parses + validates an uploaded file on the queue, producing the preview
 * (PRD FR-REG-02/06): row-level validation results are staged in `import_rows`
 * and a summary is written to the batch. NOTHING is committed to `beneficiaries`.
 *
 * Idempotent (re-parsing replaces the staged rows) and retry-safe. The payload
 * carries only the batch id — never PII.
 */
class ParseImportBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public readonly string $batchId) {}

    public function handle(SpreadsheetReader $reader, ImportRowValidator $validator): void
    {
        $batch = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->find($this->batchId);

        // Never re-parse a batch that is already being/has been committed.
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

        DB::transaction(function () use ($batch, $parsed, $validator): void {
            // Idempotent re-parse: discard any previously staged rows first.
            $batch->rows()->delete();

            $seen = ['nin' => [], 'bvn' => []];
            $total = 0;
            $valid = 0;

            foreach ($parsed['rows'] as $row) {
                $total++;
                $result = $validator->validate($row['values']);
                $errors = $result['errors'];

                // Reject in-file duplicate identifiers (the DB unique rule only
                // catches collisions with already-persisted beneficiaries).
                foreach (['nin', 'bvn'] as $key) {
                    $value = $result['payload'][$key] ?? null;
                    if ($value === null) {
                        continue;
                    }
                    if (isset($seen[$key][$value])) {
                        $errors[] = ['field' => $key, 'message' => "Duplicate {$key} within this file (see row {$seen[$key][$value]})."];
                    } else {
                        $seen[$key][$value] = $row['number'];
                    }
                }

                $isValid = $errors === [];
                $valid += $isValid ? 1 : 0;

                ImportRow::create([
                    'import_batch_id' => $batch->id,
                    'row_number' => $row['number'],
                    'original_record_id' => $this->originalRecordId($row['values']),
                    'payload' => $result['payload'],
                    'is_valid' => $isValid,
                    'errors' => $isValid ? null : $errors,
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
        ImportBatch::query()->withoutGlobalScope(MdaScope::class)->where('id', $this->batchId)->update([
            'status' => ImportStatus::Failed->value,
            'error' => Str::limit($e->getMessage(), 500),
        ]);
    }

    /**
     * @param  array<string, string>  $values
     */
    private function originalRecordId(array $values): ?string
    {
        foreach (['original_record_id', 'record_id', 'id'] as $key) {
            if (! empty($values[$key])) {
                return $values[$key];
            }
        }

        return null;
    }
}
