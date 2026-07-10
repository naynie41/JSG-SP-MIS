<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ServiceRequest;
use App\Domain\Registry\Services\ServiceRequestService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\AcceptServiceRequestRequest;
use App\Http\Requests\Registry\DeclineServiceRequestRequest;
use App\Http\Requests\Registry\StoreServiceRequestRequest;
use App\Http\Resources\ServiceRequestResource;
use App\Support\ApiResponse;
use DomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Service Request endpoints (PRD §12, FR-OWN-06/07). A non-owner MDA raises a
 * request; the OWNER MDA accepts (opening a read-access grant) or declines (with a
 * reason). Ownership never changes; this does not touch the Referral flow.
 *
 * - POST /service-requests            raise (requester)
 * - POST /service-requests/{id}/accept   owner accepts → read-access grant opens
 * - POST /service-requests/{id}/decline  owner declines (reason required)
 * - GET  /service-requests/inbox      requests routed to my MDA (to decide)
 * - GET  /service-requests/outbox     requests my MDA raised
 */
class ServiceRequestController extends Controller
{
    public function __construct(private readonly ServiceRequestService $serviceRequests) {}

    /** Requests routed TO the caller's MDA (owner) — awaiting/decided by us. */
    public function inbox(Request $request): JsonResponse
    {
        return $this->list($request, 'to_mda_id');
    }

    /** Requests raised BY the caller's MDA (requester). */
    public function outbox(Request $request): JsonResponse
    {
        return $this->list($request, 'from_mda_id');
    }

    private function list(Request $request, string $column): JsonResponse
    {
        $mdaId = $request->user()->mda_id;

        $requests = ServiceRequest::query()
            ->where($column, $mdaId)
            // Optional filters: pending-only, and scoped to one activity (§10 — the
            // activity detail view lists request-to-serve items raised under it).
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')->value()))
            ->when($request->filled('activity_id'), fn ($q) => $q->where('activity_id', $request->string('activity_id')->value()))
            ->with([
                // Reveal-safe display data (name + owner MDA); bypass the owner scope
                // since a request-to-serve is intentionally cross-MDA.
                'beneficiary' => fn ($q) => $q->withoutGlobalScope(MdaScope::class),
                'toMda' => fn ($q) => $q->withoutGlobalScope(MdaScope::class),
            ])
            ->latest()
            ->get();

        return ApiResponse::success(['service_requests' => ServiceRequestResource::collection($requests)->resolve()]);
    }

    /**
     * Raise a Service Request on a beneficiary owned by another MDA (FR-OWN-06),
     * typically from a serve-search result. Ownership is never changed. Idempotent
     * on the pending state.
     */
    public function store(StoreServiceRequestRequest $request): JsonResponse
    {
        $user = $request->user();
        if ($user->mda_id === null) {
            return ApiResponse::error('MDA_REQUIRED', 'Only users assigned to an MDA can raise a service request.', [], 422);
        }

        // Reveal-seam resolution: bypass the owner scope so a non-owner can target
        // the record. 404 (not 403) — the beneficiary is intentionally cross-MDA here.
        $beneficiary = Beneficiary::query()
            ->withoutGlobalScope(MdaScope::class)
            ->findOrFail($request->input('beneficiary_id'));

        try {
            $serviceRequest = $this->serviceRequests->request($beneficiary, $user->mda_id, $user, $request->input('reason'));
        } catch (DomainException $e) {
            return ApiResponse::error('SERVICE_REQUEST_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new ServiceRequestResource($serviceRequest))->resolve(), status: 201);
    }

    public function accept(AcceptServiceRequestRequest $request, string $serviceRequest): JsonResponse
    {
        $model = ServiceRequest::query()->findOrFail($serviceRequest);
        $this->authorize('decide', $model);

        try {
            $this->serviceRequests->accept($model, $request->user(), $request->input('reason'));
        } catch (DomainException $e) {
            return ApiResponse::error('SERVICE_REQUEST_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new ServiceRequestResource($model->fresh()))->resolve());
    }

    public function decline(DeclineServiceRequestRequest $request, string $serviceRequest): JsonResponse
    {
        $model = ServiceRequest::query()->findOrFail($serviceRequest);
        $this->authorize('decide', $model);

        try {
            $this->serviceRequests->decline($model, $request->user(), $request->input('reason'));
        } catch (DomainException $e) {
            return ApiResponse::error('SERVICE_REQUEST_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new ServiceRequestResource($model->fresh()))->resolve());
    }
}
