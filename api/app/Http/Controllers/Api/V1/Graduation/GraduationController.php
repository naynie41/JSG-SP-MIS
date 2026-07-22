<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Graduation;

use App\Domain\Graduation\Enums\CriteriaLogic;
use App\Domain\Graduation\Models\GraduationCriteria;
use App\Domain\Graduation\Models\GraduationEvent;
use App\Domain\Graduation\Services\GraduationProgressService;
use App\Domain\Graduation\Services\GraduationService;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Http\Controllers\Controller;
use App\Http\Requests\Graduation\GraduateRequest;
use App\Http\Requests\Graduation\GraduationCriteriaRequest;
use App\Http\Resources\GraduationCriteriaResource;
use App\Http\Resources\GraduationEventResource;
use App\Support\ApiResponse;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Graduation management (FR-GRD-01, FR-GRD-02): configure per-programme criteria,
 * track a beneficiary's progress toward them, record a graduation, and read the
 * graduation history. Criteria + recording require `graduation.edit`; everything
 * read-only requires `graduation.view`. Ownership is enforced by the MDA scope on the
 * criteria/enrolment/event models — an MDA only ever configures or graduates its own.
 */
class GraduationController extends Controller
{
    public function __construct(
        private readonly GraduationProgressService $progress,
        private readonly GraduationService $graduation,
    ) {}

    /** The MDA's criteria sets for a programme. */
    public function criteriaIndex(Programme $programme): JsonResponse
    {
        $criteria = GraduationCriteria::query()
            ->where('programme_id', $programme->id)
            ->latest('created_at')
            ->get();

        return ApiResponse::success([
            'criteria' => GraduationCriteriaResource::collection($criteria)->resolve(),
        ]);
    }

    /** Define a criteria set for a programme (admin-editable, not hard-coded). */
    public function criteriaStore(GraduationCriteriaRequest $request, Programme $programme): JsonResponse
    {
        $mdaId = $request->user()->mda_id;
        if ($mdaId === null) {
            return ApiResponse::error('MDA_REQUIRED', 'Only users assigned to an MDA can define graduation criteria.', [], 422);
        }

        $isActive = $request->boolean('is_active', true);

        $criteria = GraduationCriteria::create([
            'programme_id' => $programme->id,
            'owner_mda_id' => $mdaId,
            'name' => $request->string('name')->toString(),
            'logic' => CriteriaLogic::from($request->string('logic')->toString()),
            'rules' => $this->normalizeRules($request),
            'is_active' => $isActive,
            'created_by' => $request->user()->id,
        ]);

        if ($isActive) {
            $this->graduation->activateExclusively($criteria);
        }

        return ApiResponse::success(
            (new GraduationCriteriaResource($criteria))->resolve(),
            status: 201,
        );
    }

    /** Update a criteria set. */
    public function criteriaUpdate(GraduationCriteriaRequest $request, GraduationCriteria $criterion): JsonResponse
    {
        $isActive = $request->boolean('is_active', $criterion->is_active);

        $criterion->update([
            'name' => $request->string('name')->toString(),
            'logic' => CriteriaLogic::from($request->string('logic')->toString()),
            'rules' => $this->normalizeRules($request),
            'is_active' => $isActive,
        ]);

        if ($isActive) {
            $this->graduation->activateExclusively($criterion);
        }

        return ApiResponse::success((new GraduationCriteriaResource($criterion))->resolve());
    }

    /** Remove a criteria set. */
    public function criteriaDestroy(GraduationCriteria $criterion): JsonResponse
    {
        $criterion->delete();

        return ApiResponse::success(['message' => 'Graduation criteria removed.']);
    }

    /** A beneficiary/household's progress toward graduation + this enrolment's history. */
    public function progress(Enrollment $enrollment): JsonResponse
    {
        $events = GraduationEvent::query()
            ->where('enrollment_id', $enrollment->id)
            ->latest('graduated_at')
            ->get();

        return ApiResponse::success([
            'enrollment_id' => $enrollment->id,
            'status' => $enrollment->status->value,
            'progress' => $this->progress->forEnrollment($enrollment),
            'history' => GraduationEventResource::collection($events)->resolve(),
        ]);
    }

    /** Record a graduation for an enrolment (audited, notified; history preserved). */
    public function graduate(GraduateRequest $request, Enrollment $enrollment): JsonResponse
    {
        try {
            $event = $this->graduation->graduate(
                $enrollment,
                $request->filled('reason') ? $request->string('reason')->toString() : null,
                $request->user(),
            );
        } catch (DomainException $e) {
            return ApiResponse::error('GRADUATION_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success(
            (new GraduationEventResource($event))->resolve(),
            status: 201,
        );
    }

    /** The graduation history (scoped to the caller's MDAs; oversight sees all). */
    public function history(Request $request): JsonResponse
    {
        $perPage = min(max($request->integer('per_page', 20), 1), 100);

        $query = GraduationEvent::query()->latest('graduated_at');
        if ($request->filled('programme_id')) {
            $query->where('programme_id', $request->string('programme_id')->toString());
        }

        $page = $query->paginate($perPage);

        return ApiResponse::paginated(GraduationEventResource::collection($page->items())->resolve(), $page);
    }

    /**
     * Coerce the validated rules into the stored {type, threshold} shape.
     *
     * @return list<array{type: string, threshold: float}>
     */
    private function normalizeRules(Request $request): array
    {
        /** @var array<int, array<string, mixed>> $rules */
        $rules = (array) $request->input('rules', []);

        return array_values(array_map(fn (array $rule): array => [
            'type' => (string) $rule['type'],
            'threshold' => (float) ($rule['threshold'] ?? 0),
        ], $rules));
    }
}
