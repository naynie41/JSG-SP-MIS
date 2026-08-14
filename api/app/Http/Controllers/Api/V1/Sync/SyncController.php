<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Sync;

use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Sync\Enums\ConflictPolicy;
use App\Domain\Sync\Enums\SyncTrigger;
use App\Domain\Sync\Jobs\RunSyncConnector;
use App\Domain\Sync\Models\SyncConnector;
use App\Domain\Sync\Models\SyncRun;
use App\Domain\Sync\Services\ConnectorMappingService;
use App\Domain\Sync\Services\SyncEngine;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sync\ConfirmConnectorMappingRequest;
use App\Http\Requests\Sync\OfflineBatchRequest;
use App\Http\Resources\SyncConnectorResource;
use App\Http\Resources\SyncRunResource;
use App\Support\ApiResponse;
use DomainException;
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
    public function __construct(private readonly AuditLogger $audit) {}

    /** Configured connectors + their status. */
    public function connectors(Request $request): JsonResponse
    {
        $connectors = SyncConnector::query()
            ->with([
                'ownerMda:id,name',
                // Who gave the standing approval — a mapping status without a name
                // beside it is not accountability.
                'mappingConfirmedBy' => fn ($query) => $query->withoutGlobalScopes()->select('id', 'name'),
            ])
            ->latest('created_at')
            ->get();

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
    /**
     * The connector's mapping screen: a live sample from the source, suggestions, and
     * whether the source's shape has moved since the mapping was approved.
     */
    public function mapping(string $connector, ConnectorMappingService $mappings): JsonResponse
    {
        $model = SyncConnector::query()->findOrFail($connector);

        return ApiResponse::success($mappings->proposal($model));
    }

    /**
     * Approve the connector's column mapping (CLAUDE.md §11).
     *
     * Unlike a file import this confirmation STANDS for later runs — a scheduled job has
     * nobody to ask. It is bounded by the source's shape: if the fields change, the
     * connector stops until someone re-confirms.
     */
    public function confirmMapping(ConfirmConnectorMappingRequest $request, string $connector, ConnectorMappingService $mappings): JsonResponse
    {
        $model = SyncConnector::query()->findOrFail($connector);

        try {
            $mappings->confirm($model, $request->columnMap(), $request->user());
        } catch (DomainException $e) {
            return ApiResponse::error('MAPPING_INCOMPLETE', $e->getMessage(), [], 422);
        }

        return ApiResponse::success(['message' => 'Column mapping confirmed for '.$model->name.'.']);
    }

    /**
     * Enable or disable a connector.
     *
     * A connector may not be ENABLED while its mapping is unconfirmed or stale — the
     * same guard as the run, applied at configuration time so the refusal lands where
     * the decision is made rather than silently at 02:00.
     */
    public function setEnabled(Request $request, string $connector, ConnectorMappingService $mappings): JsonResponse
    {
        $model = SyncConnector::query()->findOrFail($connector);
        $enabled = $request->boolean('enabled');

        if ($enabled && ($blocked = $mappings->blockedReason($model)) !== null) {
            return ApiResponse::error('MAPPING_NOT_CONFIRMED', $blocked, [], 422);
        }

        $model->update(['enabled' => $enabled]);

        $this->audit->record($enabled ? 'sync.connector_enabled' : 'sync.connector_disabled', $model, after: [
            'connector_id' => $model->id,
            'mapping_status' => $model->mappingStatus(),
        ], actor: $request->user());

        return ApiResponse::success((new SyncConnectorResource($model->fresh()))->resolve());
    }

    public function trigger(Request $request, string $connector, ConnectorMappingService $mappings): JsonResponse
    {
        $model = SyncConnector::query()->findOrFail($connector);

        if (! $model->enabled) {
            return ApiResponse::error('CONNECTOR_DISABLED', 'This connector is disabled.', [], 422);
        }

        // Refused here as well as in the engine, so a manual trigger reports the problem
        // to the person who pressed the button rather than failing on the queue.
        if (($blocked = $mappings->blockedReason($model)) !== null) {
            return ApiResponse::error('MAPPING_NOT_CONFIRMED', $blocked, [], 422);
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
