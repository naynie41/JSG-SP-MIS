<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Programme\Enums\ProgrammeType;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Services\EnrollmentService;
use App\Domain\Programme\Services\ProgrammeMatcher;
use App\Domain\Registry\Models\Beneficiary;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\RouteAssignmentRequest;
use App\Http\Resources\EnrollmentResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Auto-route / programme matching (PRD FR-OWN-04): suggest programmes whose type +
 * eligibility fit a beneficiary's identified need, and confirm an assignment
 * (enrolment) explicitly. Suggest-then-confirm — nothing is assigned silently; the
 * confirmed assignment is audited and never transfers ownership.
 */
class BeneficiaryRoutingController extends Controller
{
    /** Suggest matching programmes across MDAs for an identified need (read-only). */
    public function suggestions(Request $request, string $beneficiary, ProgrammeMatcher $matcher): JsonResponse
    {
        $model = Beneficiary::query()->findOrFail($beneficiary);
        $this->authorize('view', $model);

        $need = $request->query('need');

        return ApiResponse::success([
            'need' => $need,
            'suggestions' => $matcher->suggest($model, is_string($need) ? $need : null),
        ]);
    }

    /** Confirm an assignment: enrol into the chosen programme (owner MDA), audited. */
    public function assign(RouteAssignmentRequest $request, string $beneficiary, EnrollmentService $enrollments, AuditLogger $audit): JsonResponse
    {
        // Cross-MDA: the target programme's MDA may serve a non-owned beneficiary.
        $model = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->findOrFail($beneficiary);
        $programme = Programme::query()->withoutGlobalScope(MdaScope::class)->findOrFail($request->input('programme_id'));

        $this->authorize('create', [Enrollment::class, $programme]);

        if ($programme->type !== ProgrammeType::Individual) {
            return ApiResponse::error('TYPE_MISMATCH', 'Auto-route assigns an individual beneficiary to an individual programme.', [], 422);
        }

        $outcome = $enrollments->enroll($programme, $model, $request->input('activity_id'), $request->user());

        if ($outcome['status'] !== 'enrolled') {
            return match (true) {
                $outcome['status'] === 'skipped' => ApiResponse::error('ENROLLMENT_EXISTS', 'The beneficiary is already enrolled in this programme.', [], 409),
                $outcome['reason'] === 'ineligible' => ApiResponse::error('INELIGIBLE', 'The beneficiary does not meet the enforced eligibility criteria.', [], 422),
                // Serving a non-owned beneficiary requires an accepted Service Request (§12, FR-OWN-06).
                default => ApiResponse::error('SERVICE_REQUEST_REQUIRED', 'Your MDA must have an accepted service request to serve this beneficiary.', [], 409),
            };
        }

        // FR-OWN-04: record the confirmed routing decision (need + target).
        $audit->record('beneficiary.routed', $model, after: [
            'need' => $request->input('need'),
            'programme_id' => $programme->id,
            'mda_id' => $programme->owner_mda_id,
            'enrollment_id' => $outcome['enrollment']?->id,
        ], actor: $request->user());

        return ApiResponse::success((new EnrollmentResource($outcome['enrollment']))->resolve(), status: 201);
    }
}
