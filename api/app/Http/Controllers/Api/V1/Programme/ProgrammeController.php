<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Programme;

use App\Domain\Access\Scopes\MdaScope;
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
 * Programme management (PRD FR-PRG-01). List/show are MDA-scoped by the model's
 * global MdaScope (oversight roles see all); create/update/archive are owner-MDA
 * only via ProgrammePolicy. Programmes are archived (status), never deleted.
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

        $mdaId = $request->user()->mda_id;
        if ($mdaId === null) {
            return ApiResponse::error('MDA_REQUIRED', 'Only users assigned to an MDA can create programmes.', [], 422);
        }

        $programme = Programme::create([
            ...$request->validated(),
            'owner_mda_id' => $mdaId,
            'created_by' => $request->user()->id,
        ]);

        return ApiResponse::success((new ProgrammeResource($programme))->resolve(), status: 201);
    }

    public function show(string $programme): JsonResponse
    {
        $model = Programme::query()->withCount('activities')->findOrFail($programme);

        $this->authorize('view', $model);

        return ApiResponse::success((new ProgrammeResource($model->load('ownerMda')))->resolve());
    }

    /**
     * Edit the programme — owner MDA only (FR-PRG-01). Resolved without the owner
     * scope so a non-owner gets 403 (the policy is the boundary), not 404.
     */
    public function update(UpdateProgrammeRequest $request, string $programme): JsonResponse
    {
        $model = Programme::query()->withoutGlobalScope(MdaScope::class)->findOrFail($programme);

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

    /** Archive the programme (owner MDA only) — reversible status change, not a delete. */
    public function archive(string $programme): JsonResponse
    {
        $model = Programme::query()->withoutGlobalScope(MdaScope::class)->findOrFail($programme);

        $this->authorize('update', $model);

        $model->update(['status' => ProgrammeStatus::Archived]);

        return ApiResponse::success((new ProgrammeResource($model->fresh()))->resolve());
    }
}
