<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Jobs\ParseImportBatch;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Services\ImportCommitter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\UploadActivityImportRequest;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\ImportBatchResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * The activity-creation wizard's OPTIONAL inline upload (PRD §10). Preview stages an
 * UNBOUND import batch (the activity is not yet saved) and runs the existing
 * validation + duplicate cascade "before saving"; confirm atomically creates the
 * activity and commits the file under it. It reuses the Phase 2 import pipeline
 * (ParseImportBatch, the /beneficiaries/imports preview + row-resolve endpoints), the
 * Phase 3 dedup + resolution, the Service Request, and the shared {@see ImportCommitter}
 * — no duplicated logic. The standalone Import Center is unaffected.
 *
 * The no-file case does not touch this controller — the wizard calls POST /activities.
 */
class ActivityImportController extends Controller
{
    /** Stage an unbound preview batch for a draft activity + file, and queue parsing/dedup. */
    public function store(UploadActivityImportRequest $request): JsonResponse
    {
        $programme = Programme::query()->findOrFail($request->input('programme_id'));
        $this->authorize('create', [Activity::class, $programme]);
        $this->authorize('create', ImportBatch::class);

        $mdaId = $request->user()->mda_id;
        if ($mdaId === null) {
            return ApiResponse::error('MDA_REQUIRED', 'Only users assigned to an MDA can create activities.', [], 422);
        }

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        $source = $request->filled('source')
            ? RegistrationSource::from($request->string('source')->value())
            : (in_array($extension, ['csv', 'txt'], true) ? RegistrationSource::Csv : RegistrationSource::Excel);

        // Unbound batch: no activity yet (§10). The activity params are stashed in
        // `draft_activity` and the activity is created + bound atomically on confirm.
        $batch = ImportBatch::create([
            'owner_mda_id' => $mdaId,
            'uploaded_by' => $request->user()->id,
            'original_filename' => $file->getClientOriginalName(),
            'stored_path' => $file->store('imports', 'local'),
            'source' => $source,
            'activity_id' => null,
            'draft_activity' => $request->activityDraft(),
            'status' => ImportStatus::Pending,
        ]);

        ParseImportBatch::dispatch($batch->id);

        return ApiResponse::success((new ImportBatchResource($batch->fresh()))->resolve(), status: 201);
    }

    /**
     * Confirm: atomically create the activity, bind the batch, and commit the file
     * under it (new beneficiaries + interventions; pending Service Requests for served
     * duplicates). Any failure rolls back everything, including the activity.
     */
    public function confirm(string $batch, ImportCommitter $committer): JsonResponse
    {
        $model = ImportBatch::query()->withoutGlobalScope(MdaScope::class)->findOrFail($batch);
        $this->authorize('commit', $model);

        if ($model->status !== ImportStatus::PreviewReady) {
            return ApiResponse::error('IMPORT_NOT_READY', 'This batch is not awaiting confirmation (current status: '.$model->status->value.').', [], 422);
        }
        if ($model->activity_id !== null || $model->draft_activity === null) {
            return ApiResponse::error('NOT_A_WIZARD_BATCH', 'This batch is not an unbound activity-wizard preview.', [], 422);
        }

        $actor = request()->user(); // the authenticated confirmer (attribution)

        $activity = DB::transaction(function () use ($model, $committer, $actor) {
            $draft = $model->draft_activity;
            $activity = Activity::create([
                ...$draft,
                'owner_mda_id' => $model->owner_mda_id, // the CREATING MDA owns it (§10)
                'created_by' => $actor?->id,
            ]);

            // Bind the batch, then commit under the just-created activity — same txn.
            $model->update(['activity_id' => $activity->id, 'draft_activity' => null]);
            $committer->commit($model->fresh(), $actor);

            return $activity;
        });

        $fresh = $model->fresh();

        return ApiResponse::success([
            'activity' => (new ActivityResource($activity->fresh()))->resolve(),
            'import' => [
                'batch' => (new ImportBatchResource($fresh))->resolve(),
                'committed_rows' => $fresh->committed_rows,
                'served_rows' => $fresh->served_rows,
                'skipped_rows' => $fresh->skipped_rows,
                'invalid_rows' => $fresh->invalid_rows,
            ],
        ], status: 201);
    }
}
