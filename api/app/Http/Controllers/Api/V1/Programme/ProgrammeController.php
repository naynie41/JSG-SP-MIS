<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Programme;

use App\Domain\Benefit\Services\LedgerAggregator;
use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Models\Programme;
use App\Http\Controllers\Controller;
use App\Http\Requests\Programme\StoreProgrammeRequest;
use App\Http\Requests\Programme\UpdateProgrammeRequest;
use App\Http\Resources\ProgrammeResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Programme catalog management (PRD §10, ARCH §12.4). Programmes are a GLOBAL,
 * unowned catalog: list/show are visible to every authenticated role;
 * create/update/archive are catalog-admin only (System Administrator / SP
 * Coordination) via ProgrammePolicy. Programmes are archived (status), never deleted.
 */
class ProgrammeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Programme::class);

        $perPage = min(max($request->integer('per_page', 25), 1), 100);
        $status = $request->input('filter.status');
        $type = $request->input('filter.type');
        $search = trim((string) $request->input('search', ''));

        $page = Programme::query()
            ->withCount('activities')
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when(is_string($status) && $status !== '', fn ($q) => $q->where('status', $status))
            ->when(is_string($type) && $type !== '', fn ($q) => $q->where('type', $type))
            ->latest('created_at')
            ->latest('id')
            ->paginate($perPage);

        return ApiResponse::paginated(ProgrammeResource::collection($page->items())->resolve(), $page);
    }

    public function store(StoreProgrammeRequest $request): JsonResponse
    {
        $this->authorize('create', Programme::class);

        // A catalog entry has no owning MDA (§10) — it is created by a catalog admin
        // and readable by all. `created_by` records the authoring user only.
        $programme = Programme::create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
        ]);

        return ApiResponse::success((new ProgrammeResource($programme))->resolve(), status: 201);
    }

    public function show(string $programme): JsonResponse
    {
        $model = Programme::query()->withCount('activities')->findOrFail($programme);

        $this->authorize('view', $model);

        return ApiResponse::success((new ProgrammeResource($model))->resolve());
    }

    /** Edit the catalog entry — catalog admin only (§10), enforced by the policy. */
    public function update(UpdateProgrammeRequest $request, string $programme): JsonResponse
    {
        $model = Programme::query()->findOrFail($programme);

        $this->authorize('update', $model);

        $model->update($request->validated());

        return ApiResponse::success((new ProgrammeResource($model->fresh()))->resolve());
    }

    /** Budget: allocated vs utilised, derived from the benefit ledger (FR-PRG-04). */
    public function budget(string $programme, LedgerAggregator $aggregator): JsonResponse
    {
        $model = Programme::query()->findOrFail($programme);
        $this->authorize('view', $model);

        return ApiResponse::success($aggregator->programmeBudget($model));
    }

    /** Archive the catalog entry (catalog admin only) — reversible status change, not a delete. */
    public function archive(string $programme): JsonResponse
    {
        $model = Programme::query()->findOrFail($programme);

        $this->authorize('update', $model);

        $model->update(['status' => ProgrammeStatus::Archived]);

        return ApiResponse::success((new ProgrammeResource($model->fresh()))->resolve());
    }
}
