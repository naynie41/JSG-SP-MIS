<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Benefit;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Jobs\CommitBenefitImport;
use App\Domain\Benefit\Jobs\ParseBenefitImport;
use App\Domain\Benefit\Models\BenefitImportBatch;
use App\Domain\Programme\Models\Activity;
use App\Domain\Registry\Enums\ImportStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Benefit\UploadBenefitImportRequest;
use App\Http\Resources\BenefitImportBatchResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Bulk benefit delivery (PRD FR-BEN-01/02, §8.3): upload a delivery list keyed to
 * an activity → preview with row-level validation → confirm → commit benefits via
 * the same recording logic as manual entry. Creates BENEFITS, not beneficiaries.
 * Scoped to (and mutable only by) the importing = delivering MDA.
 */
class BenefitImportController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', BenefitImportBatch::class);

        $perPage = min(max($request->integer('per_page', 25), 1), 100);
        $page = BenefitImportBatch::query()
            ->when($request->filled('filter.activity_id'), fn ($q) => $q->where('activity_id', $request->input('filter.activity_id')))
            ->latest('created_at')
            ->latest('id')
            ->paginate($perPage);

        return ApiResponse::paginated(BenefitImportBatchResource::collection($page->items())->resolve(), $page);
    }

    /** Upload a delivery list for an activity and queue parsing/validation. */
    public function store(UploadBenefitImportRequest $request): JsonResponse
    {
        $activity = Activity::query()->withoutGlobalScope(MdaScope::class)->findOrFail($request->input('activity_id'));
        $this->authorize('create', [BenefitImportBatch::class, $activity]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $source = in_array($extension, ['csv', 'txt'], true) ? 'csv' : 'excel';

        $batch = BenefitImportBatch::create([
            'mda_id' => $activity->owner_mda_id,
            'activity_id' => $activity->id,
            'programme_id' => $activity->programme_id,
            'uploaded_by' => $request->user()->id,
            'original_filename' => $file->getClientOriginalName(),
            'stored_path' => $file->store('benefit-imports', 'local'),
            'source' => $source,
            'status' => ImportStatus::Pending,
        ]);

        ParseBenefitImport::dispatch($batch->id);

        return ApiResponse::success((new BenefitImportBatchResource($batch->fresh()))->resolve(), status: 201);
    }

    /** The batch and its staged rows (the preview). */
    public function show(string $batch): JsonResponse
    {
        $model = BenefitImportBatch::query()->findOrFail($batch);
        $this->authorize('view', $model);

        $model->load(['rows' => fn ($query) => $query->orderBy('row_number')]);

        return ApiResponse::success((new BenefitImportBatchResource($model))->resolve());
    }

    /** Confirm the preview: queue the commit of valid rows (importing MDA only). */
    public function confirm(string $batch): JsonResponse
    {
        $model = BenefitImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($batch);
        $this->authorize('commit', $model);

        if ($model->status !== ImportStatus::PreviewReady) {
            return ApiResponse::error(
                'IMPORT_NOT_READY',
                'This batch is not awaiting confirmation (current status: '.$model->status->value.').',
                [],
                422,
            );
        }

        CommitBenefitImport::dispatch($model->id, (string) request()->user()->id);

        return ApiResponse::success((new BenefitImportBatchResource($model->fresh()))->resolve());
    }
}
