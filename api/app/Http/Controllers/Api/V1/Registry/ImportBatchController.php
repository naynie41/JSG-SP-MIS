<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Enums\ImportRowResolution;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Jobs\CommitImportBatch;
use App\Domain\Registry\Jobs\ParseImportBatch;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\ResolveImportRowRequest;
use App\Http\Requests\Registry\UploadImportRequest;
use App\Http\Resources\BeneficiaryRevealResource;
use App\Http\Resources\ImportBatchResource;
use App\Http\Resources\ImportRowResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Bulk import of beneficiaries from Excel/CSV (PRD FR-REG-02/06). Upload stages a
 * batch and queues parsing/validation; the client polls the batch for the preview
 * (row-level errors + summary); confirm queues the commit of valid rows only.
 */
class ImportBatchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', ImportBatch::class);

        $perPage = min(max($request->integer('per_page', 25), 1), 100);
        $page = ImportBatch::query()->latest('created_at')->paginate($perPage);

        return ApiResponse::paginated(ImportBatchResource::collection($page->items())->resolve(), $page);
    }

    /** Upload a file: store it, create the batch, and queue parsing/validation. */
    public function store(UploadImportRequest $request): JsonResponse
    {
        $this->authorize('create', ImportBatch::class);

        $mdaId = $request->user()->mda_id;
        if ($mdaId === null) {
            return ApiResponse::error('MDA_REQUIRED', 'Only users assigned to an MDA can import beneficiaries.', [], 422);
        }

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        // Explicit source (kobo/odk/...) selects the adapter; otherwise infer
        // plain Excel/CSV from the file extension.
        $source = $request->filled('source')
            ? RegistrationSource::from($request->string('source')->value())
            : (in_array($extension, ['csv', 'txt'], true) ? RegistrationSource::Csv : RegistrationSource::Excel);

        $storedPath = $file->store('imports', 'local');

        $batch = ImportBatch::create([
            'owner_mda_id' => $mdaId,
            'uploaded_by' => $request->user()->id,
            'original_filename' => $file->getClientOriginalName(),
            'stored_path' => $storedPath,
            'source' => $source,
            'status' => ImportStatus::Pending,
        ]);

        ParseImportBatch::dispatch($batch->id);

        return ApiResponse::success((new ImportBatchResource($batch->fresh()))->resolve(), status: 201);
    }

    /** Show the batch and its staged rows (the preview) with duplicate reveals. */
    public function show(string $batch): JsonResponse
    {
        $model = ImportBatch::query()->findOrFail($batch);

        $this->authorize('view', $model);

        $model->load(['rows' => fn ($query) => $query->orderBy('row_number')]);

        $this->attachMatchReveals($model);

        return ApiResponse::success((new ImportBatchResource($model))->resolve());
    }

    /**
     * Resolve the matched existing beneficiaries (reveal-only, cross-MDA) and the
     * within-batch peers, and attach each row's reveal payload (FR-DUP-04).
     */
    private function attachMatchReveals(ImportBatch $model): void
    {
        $rows = $model->rows;

        $registryIds = $rows
            ->flatMap(fn (ImportRow $row) => collect($row->match_candidates ?? [])
                ->where('type', 'registry')->pluck('reference'))
            ->filter()->unique()->values()->all();

        // Reveal fields only — never the full profile — even cross-MDA (serve seam).
        $beneficiaries = Beneficiary::query()
            ->withoutGlobalScope(MdaScope::class)
            ->with(['ownerMda' => fn ($query) => $query->withoutGlobalScope(MdaScope::class)->select('id', 'name')])
            ->whereIn('id', $registryIds)
            ->get()
            ->keyBy('id');

        $rowsByNumber = $rows->keyBy('row_number');

        foreach ($rows as $row) {
            /** @var ImportRow $row */
            $candidates = [];
            foreach ($row->match_candidates ?? [] as $candidate) {
                $reveal = $candidate['type'] === 'registry'
                    ? $this->registryReveal($beneficiaries, (string) $candidate['reference'])
                    : $this->batchReveal($rowsByNumber, $model, (int) $candidate['reference']);

                $candidates[] = [
                    'type' => $candidate['type'],
                    'band' => $candidate['band'],
                    'score' => $candidate['score'],
                    'matched_fields' => $candidate['matched_fields'],
                    'reveal' => $reveal,
                ];
            }

            $row->setAttribute('match_view', [
                'band' => $row->match_band ?? 'none',
                'candidates' => $candidates,
            ]);
        }
    }

    /**
     * @param  Collection<string, Beneficiary>  $beneficiaries
     * @return array<string, mixed>|null
     */
    private function registryReveal(Collection $beneficiaries, string $id): ?array
    {
        $beneficiary = $beneficiaries->get($id);

        return $beneficiary === null ? null : (new BeneficiaryRevealResource($beneficiary))->resolve();
    }

    /**
     * A within-batch peer isn't persisted yet, so its reveal is drawn from the
     * staged row (same reveal-only shape; no NIN/BVN/phone).
     *
     * @param  Collection<int, ImportRow>  $rowsByNumber
     * @return array<string, mixed>|null
     */
    private function batchReveal(Collection $rowsByNumber, ImportBatch $model, int $rowNumber): ?array
    {
        $row = $rowsByNumber->get($rowNumber);
        if ($row === null) {
            return null;
        }

        $payload = $row->payload;

        return [
            'id' => null,
            'row_number' => $row->row_number,
            'full_name' => trim(($payload['first_name'] ?? '').' '.($payload['last_name'] ?? '')),
            'owner_mda' => ['id' => $model->owner_mda_id, 'name' => null],
            'registration_source' => $model->source->value,
            'registration_date' => null,
            'lga' => $payload['lga'] ?? null,
            'ward' => $payload['ward'] ?? null,
            'status' => 'pending',
            'programmes' => [],
            'benefits' => ['summary' => null, 'items' => []],
        ];
    }

    /**
     * Resolve a flagged row (FR-DUP-05): new (with justification), link/serve, or
     * skip. Owner MDA only; only while the batch is awaiting confirmation. The
     * decision is audited (FR-DUP-06); the effect is applied on confirm.
     */
    public function resolveRow(ResolveImportRowRequest $request, string $batch, int $rowNumber, AuditLogger $audit): JsonResponse
    {
        $model = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($batch);
        $this->authorize('commit', $model);

        if ($model->status !== ImportStatus::PreviewReady) {
            return ApiResponse::error('IMPORT_NOT_READY', 'Rows can only be resolved while the batch is awaiting confirmation.', [], 422);
        }

        $row = $model->rows()->where('row_number', $rowNumber)->firstOrFail();
        $resolution = $request->enum('resolution', ImportRowResolution::class);
        $note = $request->input('note');
        $beneficiaryId = null;

        if ($resolution === ImportRowResolution::Link) {
            $beneficiaryId = (string) $request->input('beneficiary_id');
            $registryIds = collect($row->match_candidates ?? [])->where('type', 'registry')->pluck('reference')->all();
            if (! in_array($beneficiaryId, $registryIds, true)) {
                return ApiResponse::error('INVALID_MATCH', 'The selected beneficiary is not a match candidate for this row.', [], 422);
            }
        }

        $row->update([
            'resolution' => $resolution->value,
            'resolution_note' => $note,
            'resolved_beneficiary_id' => $beneficiaryId,
            'resolved_by' => $request->user()->id,
            'resolved_at' => now(),
        ]);

        // FR-DUP-06: audit the decision — actor, choice, justification, matched record.
        $audit->record('import.row_resolved', $row, after: [
            'resolution' => $resolution->value,
            'justification' => $note,
            'matched_beneficiary_id' => $beneficiaryId,
            'row_number' => $row->row_number,
            'import_batch_id' => $model->id,
        ], actor: $request->user());

        return ApiResponse::success((new ImportRowResource($row->fresh()))->resolve());
    }

    /** Confirm the preview: queue the commit of valid rows (owner MDA only). */
    public function confirm(string $batch): JsonResponse
    {
        $model = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($batch);

        $this->authorize('commit', $model);

        if ($model->status !== ImportStatus::PreviewReady) {
            return ApiResponse::error(
                'IMPORT_NOT_READY',
                'This batch is not awaiting confirmation (current status: '.$model->status->value.').',
                [],
                422,
            );
        }

        CommitImportBatch::dispatch($model->id, (string) request()->user()->id);

        return ApiResponse::success((new ImportBatchResource($model->fresh()))->resolve());
    }
}
