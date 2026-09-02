<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Programme;

use App\Domain\Benefit\Services\LedgerAggregator;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Services\ProgrammeArchiver;
use App\Http\Controllers\Controller;
use App\Http\Requests\Programme\StoreProgrammeRequest;
use App\Http\Requests\Programme\UpdateProgrammeRequest;
use App\Http\Resources\ProgrammeResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // "Participating" = the catalog programmes the CALLER has activities under.
        // Activity is MdaScoped, so whereHas resolves to the caller's own MDA without
        // this endpoint knowing anything about MDAs (an oversight role that sees all
        // activities therefore sees every programme in use). Filtering client-side
        // instead would silently drop matches beyond the first page.
        $participating = $request->boolean('filter.participating');

        $page = Programme::query()
            ->withCount($this->usageCounts())
            ->when($participating, fn ($q) => $q->whereHas('activities'))
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

    /**
     * CATALOG USAGE: how widely a global programme is taken up — the activities that
     * reference it and the distinct MDAs running those activities. Both counts run
     * through the `activities` relation, so they inherit the SAME MDA scoping the
     * caller already gets (oversight roles see across all MDAs; an MDA user sees its
     * own take-up). Programmes themselves stay global and unowned (§10).
     *
     * @return array<array-key, \Closure|string>
     */
    private function usageCounts(): array
    {
        return [
            'activities',
            'activities as mdas_count' => fn ($query) => $query->select(
                DB::raw('count(distinct owner_mda_id)')
            ),
        ];
    }

    public function show(string $programme): JsonResponse
    {
        // withArchived: an archived programme must remain viewable — activities,
        // ledger entries and graduation events still point at it.
        $model = Programme::query()->withArchived()->withCount($this->usageCounts())->findOrFail($programme);

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
        // withArchived: the historical spend under an archived programme is exactly
        // what someone reviewing the archive needs to see.
        $model = Programme::query()->withArchived()->findOrFail($programme);
        $this->authorize('view', $model);

        return ApiResponse::success($aggregator->programmeBudget($model));
    }

    /**
     * Archive the catalog entry — the "delete" for a record carrying history
     * (PRD §10). Never destroys: the programme, its activities, ledger entries and
     * graduation events all remain, queryable through the history endpoint.
     *
     * Refused with 409 while activities still run under it (see ProgrammeArchiver).
     */
    public function archive(Request $request, string $programme, ProgrammeArchiver $archiver): JsonResponse
    {
        $model = Programme::query()->withArchived()->findOrFail($programme);

        $this->authorize('update', $model);

        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        // ProgrammeHasActiveActivities is Responsable — Laravel renders its 409 with
        // the blocking activities listed, so no try/catch is needed here.
        $model = $archiver->archive($model, $request->user(), $validated['reason'] ?? null);

        return ApiResponse::success((new ProgrammeResource($model))->resolve());
    }

    /** Restore an archived catalog entry (catalog admin only). Audited. */
    public function unarchive(Request $request, string $programme, ProgrammeArchiver $archiver): JsonResponse
    {
        $model = Programme::query()->withArchived()->findOrFail($programme);

        $this->authorize('update', $model);

        return ApiResponse::success(
            (new ProgrammeResource($archiver->unarchive($model, $request->user())))->resolve()
        );
    }

    /**
     * The archive itself — retained entries, for audit and historical reporting.
     * Separate from index() because the whole point of archiving is that these do
     * NOT appear in the lists people select from.
     */
    public function archived(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Programme::class);

        $perPage = min(max($request->integer('per_page', 25), 1), 100);

        $page = Programme::query()
            ->onlyArchived()
            ->withCount($this->usageCounts())
            ->latest('archived_at')
            ->latest('id')
            ->paginate($perPage);

        return ApiResponse::paginated(ProgrammeResource::collection($page->items())->resolve(), $page);
    }
}
