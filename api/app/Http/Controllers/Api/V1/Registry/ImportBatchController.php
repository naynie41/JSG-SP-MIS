<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Jobs\CommitImportBatch;
use App\Domain\Registry\Jobs\ParseImportBatch;
use App\Domain\Registry\Models\ImportBatch;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\UploadImportRequest;
use App\Http\Resources\ImportBatchResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        return ApiResponse::success(
            ImportBatchResource::collection($page->items())->resolve(),
            [
                'pagination' => [
                    'current_page' => $page->currentPage(),
                    'per_page' => $page->perPage(),
                    'total' => $page->total(),
                    'last_page' => $page->lastPage(),
                ],
            ],
        );
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

    /** Show the batch and its staged rows (the preview). */
    public function show(string $batch): JsonResponse
    {
        $model = ImportBatch::query()->findOrFail($batch);

        $this->authorize('view', $model);

        $model->load(['rows' => fn ($query) => $query->orderBy('row_number')]);

        return ApiResponse::success((new ImportBatchResource($model))->resolve());
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
