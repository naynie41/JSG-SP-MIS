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
use App\Http\Resources\ActivityResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Activity management under a programme (PRD FR-PRG-02). Scoped to the programme
 * owner's MDA (via the activity's denormalised owner_mda_id); create/update/archive
 * are owner-MDA only via ActivityPolicy. Activities are archived, never deleted.
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
        // The parent programme is resolved without the owner scope so a non-owner
        // gets a clean 403 from the policy rather than a 404.
        $programme = Programme::query()
            ->withoutGlobalScope(MdaScope::class)
            ->findOrFail($request->input('programme_id'));

        $this->authorize('create', [Activity::class, $programme]);

        $activity = Activity::create([
            ...$request->safe()->except('programme_id'),
            'programme_id' => $programme->id,
            'owner_mda_id' => $programme->owner_mda_id, // inherit ownership from the programme
            'created_by' => $request->user()->id,
        ]);

        return ApiResponse::success((new ActivityResource($activity))->resolve(), status: 201);
    }

    public function show(string $activity): JsonResponse
    {
        $model = Activity::query()->findOrFail($activity);

        $this->authorize('view', $model);

        return ApiResponse::success((new ActivityResource($model))->resolve());
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
