<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Programme;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Services\LedgerAggregator;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Services\ProgrammeApprovalService;
use App\Domain\Programme\Services\ProgrammeArchiver;
use App\Http\Controllers\Controller;
use App\Http\Requests\Programme\DecideProgrammeRequest;
use App\Http\Requests\Programme\StoreProgrammeRequest;
use App\Http\Requests\Programme\UpdateProgrammeRequest;
use App\Http\Resources\ProgrammeResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Programme catalog management (PRD §10, ARCH §12.4, revised).
 *
 * The catalog has two halves. The CENTRAL half is unowned and readable by every
 * role, created and edited by catalog administrators (System Administrator / SP
 * Coordination) — that is what the whole catalog used to be. The other half is
 * MDA-OWNED: an MDA creates a programme for itself, which no other MDA can see (the
 * MDA scope on the model decides that, not this controller) and which carries no
 * work until a System Administrator approves it.
 *
 * Programmes are archived (status), never deleted.
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
        $approval = $request->input('filter.approval');
        $mine = $request->user()?->mda_id;

        $page = Programme::query()
            ->with('ownerMda:id,name')
            ->withCount($this->usageCounts())
            // "Participating" also covers a programme the MDA created for itself,
            // which has no activities yet by definition — without this the author
            // of a pending programme could not see their own submission.
            ->when($participating, fn ($q) => $q->where(function ($q) use ($mine): void {
                $q->whereHas('activities');
                if ($mine !== null) {
                    $q->orWhere('owner_mda_id', $mine);
                }
            }))
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when(is_string($status) && $status !== '', fn ($q) => $q->where('status', $status))
            ->when(is_string($approval) && $approval !== '', fn ($q) => $q->where('approval_status', $approval))
            ->when(is_string($type) && $type !== '', fn ($q) => $q->where('type', $type))
            ->latest('created_at')
            ->latest('id')
            ->paginate($perPage);

        return ApiResponse::paginated(ProgrammeResource::collection($page->items())->resolve(), $page);
    }

    /**
     * Create a programme. WHO is creating decides what kind (§10, revised):
     *
     *  - a catalog administrator creates a CENTRAL entry — unowned, readable by every
     *    MDA, approved on the spot, exactly as before;
     *  - anyone else with the permission (the MDA Admin) creates one owned BY THEIR
     *    OWN MDA, which starts waiting for approval and carries no work until a
     *    System Administrator decides.
     *
     * The owner is taken from the authenticated user, never from the request body:
     * a client cannot create a programme for another MDA by naming it.
     */
    public function store(StoreProgrammeRequest $request, ProgrammeApprovalService $approvals): JsonResponse
    {
        $this->authorize('create', Programme::class);

        $user = $request->user();
        $central = $this->isCatalogAdmin($user);

        $programme = Programme::create([
            ...$request->validated(),
            'owner_mda_id' => $central ? null : $user->mda_id,
            'created_by' => $user->id,
        ]);

        if (! $central) {
            $approvals->submit($programme, $user);
        }

        return ApiResponse::success((new ProgrammeResource($programme->fresh()))->resolve(), status: 201);
    }

    /** Re-submit a programme that was sent back, after the MDA has addressed it. */
    public function submit(string $programme, ProgrammeApprovalService $approvals): JsonResponse
    {
        $model = Programme::query()->findOrFail($programme);

        $this->authorize('submit', $model);

        $approvals->submit($model, request()->user());

        return ApiResponse::success((new ProgrammeResource($model->fresh()))->resolve());
    }

    /** Clear an MDA's programme for use — System Administrator only (policy). */
    public function approve(DecideProgrammeRequest $request, string $programme, ProgrammeApprovalService $approvals): JsonResponse
    {
        $model = Programme::query()->findOrFail($programme);

        $this->authorize('decide', $model);

        $approvals->approve($model, $request->user(), $request->input('decision_note'));

        return ApiResponse::success((new ProgrammeResource($model->fresh()))->resolve());
    }

    /** Send it back with a reason — System Administrator only (policy). */
    public function reject(DecideProgrammeRequest $request, string $programme, ProgrammeApprovalService $approvals): JsonResponse
    {
        $model = Programme::query()->findOrFail($programme);

        $this->authorize('decide', $model);

        $approvals->reject($model, $request->user(), (string) $request->input('decision_note'));

        return ApiResponse::success((new ProgrammeResource($model->fresh()))->resolve());
    }

    /** Catalog administrators create the central, unowned catalog (§10). */
    private function isCatalogAdmin(User $user): bool
    {
        return in_array($user->role?->key, [
            RoleKey::SystemAdministrator->value,
            RoleKey::SpCoordination->value,
        ], true);
    }

    /**
     * CATALOG USAGE: how widely a global programme is taken up — the activities that
     * reference it and the distinct MDAs running those activities. Both counts run
     * through the `activities` relation, so they inherit the SAME MDA scoping the
     * caller already gets (oversight roles see across all MDAs; an MDA user sees its
     * own take-up). Counts cover BOTH kinds of entry — a central one and an MDA's own —
     * because an MDA runs both the same way, through activities it owns (§10).
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
        $model = Programme::query()->withArchived()->with('ownerMda:id,name')->withCount($this->usageCounts())->findOrFail($programme);

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
            ->with('ownerMda:id,name')
            ->withCount($this->usageCounts())
            ->latest('archived_at')
            ->latest('id')
            ->paginate($perPage);

        return ApiResponse::paginated(ProgrammeResource::collection($page->items())->resolve(), $page);
    }
}
