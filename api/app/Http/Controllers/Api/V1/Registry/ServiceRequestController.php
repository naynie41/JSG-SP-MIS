<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryServiceGrant;
use App\Domain\Registry\Models\ServiceRequest;
use App\Domain\Registry\Services\ServiceRequestService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\AcceptServiceRequestRequest;
use App\Http\Requests\Registry\DeclineServiceRequestRequest;
use App\Http\Requests\Registry\RevokeServiceGrantRequest;
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
 * - POST /service-grants/{grant}/revoke  owner withdraws the read grant (reason optional)
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
                // Reveal-safe display data (name + both MDAs); bypass the owner scope
                // since a request-to-serve is intentionally cross-MDA. The owner
                // cannot judge a request identified only by UUID, so the requesting
                // MDA is loaded alongside the owning one.
                'beneficiary' => fn ($q) => $q->withoutGlobalScope(MdaScope::class),
                'toMda' => fn ($q) => $q->withoutGlobalScope(MdaScope::class),
                'fromMda' => fn ($q) => $q->withoutGlobalScope(MdaScope::class),
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

    /**
     * Who currently holds — or recently held — cross-MDA access to this beneficiary
     * (FR-OWN-07). Owner MDA (or an all-MDA oversight role) only.
     *
     * Both active and revoked grants are returned: the owner is accountable for who has
     * seen this record, and a history that hides withdrawn access would answer "who can
     * read this today" while losing "who could read it last month".
     */
    public function grants(Request $request, string $beneficiary): JsonResponse
    {
        $model = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->findOrFail($beneficiary);
        $this->authorize('viewGrants', $model);

        /*
         * Both relations are loaded UNSCOPED. `Mda` and `User` are themselves
         * ScopedToMda, so the owner asking "who has access to my record" would otherwise
         * get a null MDA — the scope hides precisely the other party this panel exists to
         * name. Only id + name are exposed, which is the answer to the question and
         * carries no beneficiary data.
         */
        $grants = BeneficiaryServiceGrant::query()
            ->withoutGlobalScope(MdaScope::class)
            ->with([
                'mda' => fn ($q) => $q->withoutGlobalScopes()->select('id', 'name'),
                'revokedBy' => fn ($q) => $q->withoutGlobalScopes()->select('id', 'name'),
            ])
            ->where('beneficiary_id', $model->id)
            ->orderByDesc('granted_at')
            ->get()
            ->map(fn (BeneficiaryServiceGrant $grant): array => [
                'id' => $grant->id,
                // Both non-nullable on the model: `mda_id` is a NOT NULL cascade-delete
                // FK, and `granted_at` is set when the grant is opened.
                'mda' => ['id' => $grant->mda->id, 'name' => $grant->mda->name],
                'service_request_id' => $grant->service_request_id,
                'granted_at' => $grant->granted_at->toIso8601String(),
                'active' => $grant->isActive(),
                'revoked_at' => $grant->revoked_at?->toIso8601String(),
                'revoked_by' => $grant->revokedBy?->name,
                'revocation_reason' => $grant->revocation_reason,
            ])
            ->all();

        return ApiResponse::success(['grants' => $grants]);
    }

    /**
     * Withdraw a cross-MDA read grant (FR-OWN-07). Owner MDA, or a System Administrator
     * as override; idempotent.
     *
     * The grant is resolved WITHOUT the MDA global scope on purpose: `ScopedToMda` binds
     * it to `mda_id`, the GRANTED MDA, so the scope hides the grant from the very owner
     * entitled to revoke it. Resolving it unscoped and then running the policy puts the
     * decision where it belongs — a stranger gets 403 from the policy, not a misleading
     * 404 from the scope.
     */
    public function revoke(RevokeServiceGrantRequest $request, string $grant): JsonResponse
    {
        $model = BeneficiaryServiceGrant::query()
            ->withoutGlobalScope(MdaScope::class)
            ->findOrFail($grant);

        $this->authorize('revoke', $model);

        $revoked = $this->serviceRequests->revokeGrant($model, $request->user(), $request->input('reason'));

        return ApiResponse::success([
            'id' => $model->id,
            'revoked' => $revoked,
            'revoked_at' => $model->revoked_at?->toIso8601String(),
            'message' => $revoked
                ? 'Cross-MDA read access revoked.'
                : 'This access was already revoked; nothing changed.',
        ]);
    }
}
