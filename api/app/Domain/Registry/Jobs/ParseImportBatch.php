<?php

declare(strict_types=1);

namespace App\Domain\Registry\Jobs;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Matching\Enums\ExactMatchBehaviour;
use App\Domain\Matching\Models\MatchingConfig;
use App\Domain\Matching\Services\MatchingConfigService;
use App\Domain\Registry\Enums\ImportRowResolution;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Imports\Adapters\SourceAdapterRegistry;
use App\Domain\Registry\Imports\ImportRowValidator;
use App\Domain\Registry\Imports\SpreadsheetReader;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use App\Domain\Registry\Services\BatchDuplicateScreener;
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
 * carries only the batch id — never PII. The batch's source adapter maps each
 * raw record onto the canonical schema before the shared validation runs, and the
 * duplicate check (FR-DUP-01) annotates each row against the registry and earlier
 * rows in the batch.
 */
class ParseImportBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public readonly string $batchId) {}

    public function handle(SpreadsheetReader $reader, ImportRowValidator $validator, SourceAdapterRegistry $adapters, BatchDuplicateScreener $screener, MatchingConfigService $configs): void
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

        $adapter = $adapters->for($batch->source);
        $matchConfig = $configs->activeOrNull(); // null → matching not configured; skip screening

        DB::transaction(function () use ($batch, $parsed, $validator, $adapter, $screener, $matchConfig): void {
            // Idempotent re-parse: discard any previously staged rows first.
            $batch->rows()->delete();

            $seen = ['nin' => [], 'bvn' => []];
            $total = 0;
            $valid = 0;

            foreach ($parsed['rows'] as $row) {
                $total++;
                // Map the raw source record onto the canonical schema, then run
                // the SAME validation as manual registration.
                $mapped = $adapter->map($row['values']);
                $result = $validator->validate($mapped);
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

                // Duplicate check (FR-DUP-01): registry + earlier rows in the batch.
                $match = $matchConfig !== null
                    ? $screener->screen($result['payload'], $row['number'], $matchConfig)
                    : ['band' => null, 'candidates' => null];

                // Deterministic-exact behaviour (FR-DUP-03/05): auto-link when so
                // configured and there is an existing exact registry match.
                [$resolution, $resolvedBeneficiaryId] = $this->autoResolve($match, $matchConfig);

                ImportRow::create([
                    'import_batch_id' => $batch->id,
                    'row_number' => $row['number'],
                    'original_record_id' => $mapped['original_record_id'] ?? null,
                    'household_ref' => $mapped['household_ref'] ?? null,
                    'household_role' => $mapped['household_role'] ?? null,
                    'household_head' => $this->isTruthy($mapped['household_head'] ?? null),
                    'payload' => $result['payload'],
                    'is_valid' => $isValid,
                    'errors' => $isValid ? null : $errors,
                    'match_band' => $match['band'],
                    'match_candidates' => $match['candidates'],
                    'resolution' => $resolution,
                    'resolved_beneficiary_id' => $resolvedBeneficiaryId,
                    'resolved_at' => $resolution !== null ? now() : null,
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

    /** Interpret a source "head of household" flag. */
    private function isTruthy(?string $value): bool
    {
        return $value !== null && in_array(strtolower(trim($value)), ['1', 'true', 'yes', 'y', 'head'], true);
    }

    /**
     * Pre-resolve a row to LINK when the config auto-links exact matches and an
     * existing registry beneficiary matched exactly. Otherwise leaves it for the
     * officer to decide.
     *
     * @param  array{band: ?string, candidates: ?list<array<string, mixed>>}  $match
     * @return array{0: ?string, 1: ?string} [resolution, resolved_beneficiary_id]
     */
    private function autoResolve(array $match, ?MatchingConfig $config): array
    {
        if ($config === null || $match['band'] !== 'exact' || $config->exact_match_behaviour !== ExactMatchBehaviour::AutoLink) {
            return [null, null];
        }

        foreach ($match['candidates'] ?? [] as $candidate) {
            if (($candidate['type'] ?? null) === 'registry' && ($candidate['band'] ?? null) === 'exact') {
                return [ImportRowResolution::Link->value, (string) $candidate['reference']];
            }
        }

        return [null, null];
    }
}
