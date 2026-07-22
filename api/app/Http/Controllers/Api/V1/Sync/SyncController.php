<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Sync;

use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Sync\Enums\ConflictPolicy;
use App\Domain\Sync\Enums\SyncTrigger;
use App\Domain\Sync\Jobs\RunSyncConnector;
use App\Domain\Sync\Models\SyncConnector;
use App\Domain\Sync\Models\SyncRun;
use App\Domain\Sync\Services\SyncEngine;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sync\OfflineBatchRequest;
use App\Http\Resources\SyncConnectorResource;
use App\Http\Resources\SyncRunResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Data-synchronization administration (FR-DSH-02, FR-REG-08). Read the configured
 * connectors, the run history + per-record logs, trigger a connector manually, and
 * flush offline-captured batches. Connector sync is System-Admin territory
 * (`sync.run`); offline-batch flush is done by the capturing MDA (`beneficiary.create`).
 */
class SyncController extends Controller
{
    /** Configured connectors + their status. */
    public function connectors(Request $request): JsonResponse
    {
        $connectors = SyncConnector::query()->with('ownerMda:id,name')->latest('created_at')->get();

        return ApiResponse::success(['connectors' => SyncConnectorResource::collection($connectors)->resolve()]);
    }

    /** Recent sync runs (the status surfaced to admins). */
    public function runs(Request $request): JsonResponse
    {
        $perPage = min(max($request->integer('per_page', 20), 1), 100);
        $page = SyncRun::query()->latest('created_at')->paginate($perPage);

        return ApiResponse::paginated(SyncRunResource::collection($page->items())->resolve(), $page);
    }

    /** One run with its full per-record outcome log. */
    public function run(string $run): JsonResponse
    {
        $model = SyncRun::query()->with(['rows' => fn ($q) => $q->orderBy('created_at')])->findOrFail($run);

        return ApiResponse::success((new SyncRunResource($model))->resolve());
    }

    /** Manually trigger a connector's sync (queued, idempotent, unique per connector). */
    public function trigger(Request $request, string $connector): JsonResponse
    {
        $model = SyncConnector::query()->findOrFail($connector);

        if (! $model->enabled) {
            return ApiResponse::error('CONNECTOR_DISABLED', 'This connector is disabled.', [], 422);
        }

        RunSyncConnector::dispatch($model->id, SyncTrigger::Manual->value, $request->user()->id);

        return ApiResponse::success(['message' => 'Sync started for '.$model->name.'.'], status: 202);
    }

    /**
     * Flush an offline-captured batch (FR-REG-08). Runs the SAME pipeline as import;
     * per-record idempotency keys mean re-flushing the same batch never double-inserts.
     */
    public function offlineBatch(OfflineBatchRequest $request, SyncEngine $engine): JsonResponse
    {
        $mdaId = $request->user()->mda_id;
        if ($mdaId === null) {
            return ApiResponse::error('MDA_REQUIRED', 'Only users assigned to an MDA can flush an offline batch.', [], 422);
        }

        $policy = $request->filled('conflict_policy')
            ? ConflictPolicy::from((string) $request->input('conflict_policy'))
            : ConflictPolicy::from((string) config('sync.default_conflict_policy', 'flag_for_review'));

        /** @var array<int, array<string, mixed>> $records */
        $records = $request->input('records');

        $run = $engine->runOfflineBatch(
            $mdaId,
            RegistrationSource::from((string) $request->input('source')),
            $records,
            $policy,
            $request->user(),
        );

        return ApiResponse::success((new SyncRunResource($run->load('rows')))->resolve(), status: 201);
    }
}
