<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Programme;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Services\LedgerAggregator;
use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Http\Controllers\Controller;
use App\Http\Requests\Programme\StoreActivityRequest;
use App\Http\Requests\Programme\UpdateActivityRequest;
use App\Http\Resources\ActivityDetailResource;
use App\Http\Resources\ActivityResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Activity management (PRD §10, ARCH §12.4, FR-PRG-02). An activity runs a global
 * catalog programme and is owned by the CREATING MDA (its own owner_mda_id + scope);
 * create/update/archive are owner-MDA only via ActivityPolicy. Archived, never deleted.
 */
class ActivityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Activity::class);

        $perPage = min(max($request->integer('per_page', 25), 1), 100);
        $programmeId = $request->input('filter.programme_id');
        $status = $request->input('filter.status');

        $page = Activity::query()
            ->when(is_string($programmeId) && $programmeId !== '', fn ($q) => $q->where('programme_id', $programmeId))
            ->when(is_string($status) && $status !== '', fn ($q) => $q->where('status', $status))
            ->latest('created_at')
            ->latest('id')
            ->paginate($perPage);

        return ApiResponse::paginated(ActivityResource::collection($page->items())->resolve(), $page);
    }

    public function store(StoreActivityRequest $request): JsonResponse
    {
        // Confirm the catalog programme exists (a valid, global reference).
        $programme = Programme::query()->findOrFail($request->input('programme_id'));

        $this->authorize('create', [Activity::class, $programme]);

        $mdaId = $request->user()->mda_id;
        if ($mdaId === null) {
            return ApiResponse::error('MDA_REQUIRED', 'Only users assigned to an MDA can create activities.', [], 422);
        }

        $activity = Activity::create([
            ...$request->safe()->except('programme_id'),
            'programme_id' => $programme->id,
            'owner_mda_id' => $mdaId, // the CREATING MDA owns the activity (§10)
            'created_by' => $request->user()->id,
        ]);

        return ApiResponse::success((new ActivityResource($activity))->resolve(), status: 201);
    }

    /**
     * The full "View Activity" detail (PRD §10): catalog programme, all activity fields,
     * target vs actual beneficiary counts, the beneficiaries/interventions under it, the
     * import/validation summary of its batch(es), and the attached request-to-serve items.
     * Owner-MDA scoped (MdaScope) + view policy; NIN/BVN masked, foreign beneficiaries
     * shown reveal-only.
     */
    public function show(string $activity): JsonResponse
    {
        $model = Activity::query()->with('programme')->findOrFail($activity);

        $this->authorize('view', $model);

        return ApiResponse::success((new ActivityDetailResource($model))->resolve());
    }

    public function update(UpdateActivityRequest $request, string $activity): JsonResponse
    {
        $model = Activity::query()->withoutGlobalScope(MdaScope::class)->findOrFail($activity);

        $this->authorize('update', $model);

        $model->update($request->validated());

        return ApiResponse::success((new ActivityResource($model->fresh()))->resolve());
    }

    /** Budget: allocated vs utilised, derived from the benefit ledger (FR-PRG-04). */
    public function budget(string $activity, LedgerAggregator $aggregator): JsonResponse
    {
        $model = Activity::query()->findOrFail($activity);
        $this->authorize('view', $model);

        return ApiResponse::success($aggregator->activityBudget($model));
    }

    /** Archive the activity (owner MDA only) — reversible status change, not a delete. */
    public function archive(string $activity): JsonResponse
    {
        $model = Activity::query()->withoutGlobalScope(MdaScope::class)->findOrFail($activity);

        $this->authorize('update', $model);

        $model->update(['status' => ActivityStatus::Archived]);

        return ApiResponse::success((new ActivityResource($model->fresh()))->resolve());
    }
}
