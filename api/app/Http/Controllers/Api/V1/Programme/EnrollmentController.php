<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Programme;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Enums\EnrollmentStatus;
use App\Domain\Programme\Enums\ProgrammeType;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Services\EnrollmentService;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Http\Controllers\Controller;
use App\Http\Requests\Programme\BulkEnrollmentRequest;
use App\Http\Requests\Programme\StoreEnrollmentRequest;
use App\Http\Requests\Programme\UpdateEnrollmentRequest;
use App\Http\Resources\EnrollmentResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Beneficiary/household enrollment into programmes (PRD FR-PRG-03). Enrollment is
 * done by the programme's owner MDA; the serve seam (EnrollmentService) decides
 * whether a non-owned beneficiary may be enrolled — never changing ownership.
 * List is MDA-scoped by the enrolling MDA. Supports single + bulk assignment.
 */
class EnrollmentController extends Controller
{
    public function __construct(private readonly EnrollmentService $enrollments) {}

    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Enrollment::class);

        $perPage = min(max($request->integer('per_page', 25), 1), 100);
        $programmeId = $request->input('filter.programme_id');
        $status = $request->input('filter.status');
        $beneficiaryId = $request->input('filter.beneficiary_id');
        $flagged = $request->input('filter.eligibility_flagged');

        $page = Enrollment::query()
            ->when(is_string($programmeId) && $programmeId !== '', fn ($q) => $q->where('programme_id', $programmeId))
            ->when(is_string($status) && $status !== '', fn ($q) => $q->where('status', $status))
            ->when(is_string($beneficiaryId) && $beneficiaryId !== '', fn ($q) => $q->where('beneficiary_id', $beneficiaryId))
            ->when($flagged !== null && $flagged !== '', fn ($q) => $q->where('eligibility_flagged', filter_var($flagged, FILTER_VALIDATE_BOOL)))
            ->latest('created_at')
            ->latest('id')
            ->paginate($perPage);

        return ApiResponse::paginated(EnrollmentResource::collection($page->items())->resolve(), $page);
    }

    public function store(StoreEnrollmentRequest $request, string $programme): JsonResponse
    {
        $model = $this->programme($programme);
        $this->authorize('create', [Enrollment::class, $model]);

        $isBeneficiary = $request->filled('beneficiary_id');
        if (($mismatch = $this->typeMismatch($model, $isBeneficiary)) !== null) {
            return $mismatch;
        }
        if (($badActivity = $this->activityMismatch($model, $request->input('activity_id'))) !== null) {
            return $badActivity;
        }

        $target = $isBeneficiary
            ? Beneficiary::query()->withoutGlobalScope(MdaScope::class)->findOrFail($request->input('beneficiary_id'))
            : Household::query()->withoutGlobalScope(MdaScope::class)->findOrFail($request->input('household_id'));

        $outcome = $this->enrollments->enroll($model, $target, $request->input('activity_id'), $request->user());

        return match ($outcome['status']) {
            'enrolled' => ApiResponse::success((new EnrollmentResource($outcome['enrollment']))->resolve(), status: 201),
            'skipped' => ApiResponse::error('ENROLLMENT_EXISTS', 'This target is already enrolled in the programme.', [], 409),
            default => $outcome['reason'] === 'ineligible'
                ? ApiResponse::error('INELIGIBLE', 'The target does not meet the enforced eligibility criteria.', [['field' => 'eligibility', 'message' => 'Unmet: '.implode(', ', $outcome['unmet'])]], 422)
                : ApiResponse::error('SERVE_ACCESS_REQUIRED', 'Your MDA does not own or serve this beneficiary.', [], 403),
        };
    }

    public function bulk(BulkEnrollmentRequest $request, string $programme): JsonResponse
    {
        $model = $this->programme($programme);
        $this->authorize('create', [Enrollment::class, $model]);

        $isBeneficiary = count((array) $request->input('beneficiary_ids', [])) > 0;
        if (($mismatch = $this->typeMismatch($model, $isBeneficiary)) !== null) {
            return $mismatch;
        }
        if (($badActivity = $this->activityMismatch($model, $request->input('activity_id'))) !== null) {
            return $badActivity;
        }

        /** @var list<string> $ids */
        $ids = $isBeneficiary ? $request->input('beneficiary_ids') : $request->input('household_ids');
        $targets = ($isBeneficiary ? Beneficiary::query() : Household::query())
            ->withoutGlobalScope(MdaScope::class)
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        $results = [];
        $counts = ['enrolled' => 0, 'skipped' => 0, 'rejected' => 0];
        foreach ($ids as $id) {
            $target = $targets->get($id);
            if ($target === null) {
                $results[] = ['target_id' => $id, 'status' => 'rejected', 'reason' => 'not_found'];
                $counts['rejected']++;

                continue;
            }
            $outcome = $this->enrollments->enroll($model, $target, $request->input('activity_id'), $request->user());
            $counts[$outcome['status']]++;
            $results[] = [
                'target_id' => $id,
                'status' => $outcome['status'],
                'reason' => $outcome['reason'],
                'enrollment_id' => $outcome['enrollment']?->id,
                'eligibility_flagged' => $outcome['enrollment'] !== null && $outcome['enrollment']->eligibility_flagged,
            ];
        }

        return ApiResponse::success([
            'programme_id' => $model->id,
            'subject_type' => $isBeneficiary ? 'beneficiary' : 'household',
            'requested' => count($ids),
            ...$counts,
            'results' => $results,
        ]);
    }

    public function update(UpdateEnrollmentRequest $request, string $enrollment): JsonResponse
    {
        $model = Enrollment::query()->withoutGlobalScope(MdaScope::class)->findOrFail($enrollment);
        $this->authorize('update', $model);

        $status = $request->enum('status', EnrollmentStatus::class);
        $isClosing = in_array($status, [EnrollmentStatus::Exited, EnrollmentStatus::Graduated], true);

        $model->update([
            'status' => $status,
            'exit_reason' => $request->input('exit_reason'),
            'exited_on' => $isClosing ? now()->toDateString() : null,
        ]);

        return ApiResponse::success((new EnrollmentResource($model->fresh()))->resolve());
    }

    /** Resolve the programme without the owner scope so the policy is the boundary. */
    private function programme(string $id): Programme
    {
        return Programme::query()->withoutGlobalScope(MdaScope::class)->findOrFail($id);
    }

    private function typeMismatch(Programme $programme, bool $isBeneficiary): ?JsonResponse
    {
        $ok = $programme->type === ProgrammeType::Individual ? $isBeneficiary : ! $isBeneficiary;
        if ($ok) {
            return null;
        }

        $need = $programme->type === ProgrammeType::Individual ? 'beneficiaries' : 'households';

        return ApiResponse::error('TYPE_MISMATCH', "A {$programme->type->value} programme enrolls {$need}.", [], 422);
    }

    private function activityMismatch(Programme $programme, ?string $activityId): ?JsonResponse
    {
        if ($activityId === null) {
            return null;
        }
        $belongs = Activity::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('id', $activityId)
            ->where('programme_id', $programme->id)
            ->exists();

        return $belongs ? null : ApiResponse::error('ACTIVITY_MISMATCH', 'The activity does not belong to this programme.', [], 422);
    }
}
