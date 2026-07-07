<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Grievance;

use App\Domain\Access\Models\User;
use App\Domain\Grievance\Enums\GrievanceCategory;
use App\Domain\Grievance\Enums\GrievanceChannel;
use App\Domain\Grievance\Exceptions\InvalidGrievanceTransitionException;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Grievance\Services\GrievanceService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Grievance\AssignGrievanceRequest;
use App\Http\Requests\Grievance\StoreGrievanceRequest;
use App\Http\Requests\Grievance\TransitionGrievanceRequest;
use App\Http\Resources\GrievanceResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Grievance endpoints (PRD FR-GRM-01/02, §8.4). MDA-scoped: only the handling MDA
 * (+ oversight) sees a grievance. Staff capture on behalf of beneficiaries; the
 * handling MDA's staff assign + move it to resolution. Invalid transitions → 422.
 */
class GrievanceController extends Controller
{
    public function __construct(private readonly GrievanceService $grievances) {}

    /** Grievances handled by the caller's MDA (`filter[status]`, `filter[category]`, `filter[assignee]`). */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Grievance::class);

        $status = $request->input('filter.status');
        $category = $request->input('filter.category');
        $assignee = $request->input('filter.assignee');
        $perPage = min(max($request->integer('per_page', 25), 1), 100);

        $page = Grievance::query()
            ->when(is_string($status) && $status !== '', fn ($q) => $q->where('status', $status))
            ->when(is_string($category) && $category !== '', fn ($q) => $q->where('category', $category))
            ->when($assignee === 'me', fn ($q) => $q->where('assignee_user_id', $request->user()->id))
            ->latest('created_at')
            ->paginate($perPage);

        return ApiResponse::paginated(GrievanceResource::collection($page->items())->resolve(), $page);
    }

    public function show(string $grievance): JsonResponse
    {
        $model = Grievance::query()->findOrFail($grievance);
        $this->authorize('view', $model);

        return ApiResponse::success((new GrievanceResource($model))->resolve());
    }

    /** Capture a grievance (staff) — handling MDA is the caller's MDA. */
    public function store(StoreGrievanceRequest $request): JsonResponse
    {
        $this->authorize('create', Grievance::class);

        $grievance = $this->grievances->create(
            $request->user(),
            $request->enum('category', GrievanceCategory::class),
            $request->enum('channel', GrievanceChannel::class),
            (string) $request->input('description'),
            $request->input('beneficiary_id'),
        );

        return ApiResponse::success((new GrievanceResource($grievance))->resolve(), status: 201);
    }

    public function assign(AssignGrievanceRequest $request, string $grievance): JsonResponse
    {
        $model = Grievance::query()->findOrFail($grievance);
        $this->authorize('manage', $model);

        // The assignee must be visible to the caller (their own MDA).
        $assignee = User::query()->findOrFail($request->input('assignee_user_id'));

        return $this->run(fn () => $this->grievances->assign($model, $assignee, $request->user()));
    }

    public function start(TransitionGrievanceRequest $request, string $grievance): JsonResponse
    {
        return $this->act($request, $grievance, fn (Grievance $g) => $this->grievances->start($g, $request->user()));
    }

    public function resolve(TransitionGrievanceRequest $request, string $grievance): JsonResponse
    {
        return $this->act($request, $grievance, fn (Grievance $g) => $this->grievances->resolve($g, $request->user(), (string) $request->input('resolution_notes')));
    }

    public function close(TransitionGrievanceRequest $request, string $grievance): JsonResponse
    {
        return $this->act($request, $grievance, fn (Grievance $g) => $this->grievances->close($g, $request->user(), $request->input('resolution_notes')));
    }

    /**
     * @param  callable(Grievance): Grievance  $action
     */
    private function act(TransitionGrievanceRequest $request, string $grievance, callable $action): JsonResponse
    {
        $model = Grievance::query()->findOrFail($grievance);
        $this->authorize('manage', $model);

        return $this->run(fn () => $action($model));
    }

    /**
     * @param  callable(): Grievance  $action
     */
    private function run(callable $action): JsonResponse
    {
        try {
            $grievance = $action();
        } catch (InvalidGrievanceTransitionException $e) {
            return ApiResponse::error('INVALID_TRANSITION', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new GrievanceResource($grievance->fresh()))->resolve());
    }
}
