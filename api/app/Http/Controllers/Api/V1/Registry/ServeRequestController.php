<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ServeRequest;
use App\Domain\Registry\Services\ServeRequestService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\DecideServeRequestRequest;
use App\Http\Requests\Registry\RaiseServeRequestRequest;
use App\Http\Resources\ServeRequestResource;
use App\Support\ApiResponse;
use DomainException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Request-to-serve endpoints (PRD FR-DUP-05). Requests are raised during import
 * resolution; here either party lists them and the OWNER MDA accepts/declines.
 * Accepting grants serve access — it never changes ownership.
 */
class ServeRequestController extends Controller
{
    public function __construct(private readonly ServeRequestService $serveRequests) {}

    /** Serve requests involving the caller's MDA (as requester or owner). */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $requests = ServeRequest::query()
            ->when(! $user->canAccessAllMdas(), fn (Builder $query) => $query->where(
                fn (Builder $inner) => $inner->where('from_mda_id', $user->mda_id)->orWhere('to_mda_id', $user->mda_id),
            ))
            ->latest()
            ->get();

        return ApiResponse::success(['serve_requests' => ServeRequestResource::collection($requests)->resolve()]);
    }

    /**
     * Raise a request-to-serve on a beneficiary owned by another MDA (FR-DUP-05,
     * FR-OWN-03), typically from a serve search result. Reuses the exact 3.5 flow
     * and auditing; ownership is never changed. Idempotent on the pending state.
     */
    public function store(RaiseServeRequestRequest $request): JsonResponse
    {
        $user = $request->user();
        if ($user->mda_id === null) {
            return ApiResponse::error('MDA_REQUIRED', 'Only users assigned to an MDA can request to serve a beneficiary.', [], 422);
        }

        // Reveal-seam resolution: bypass the owner scope so a non-owner can target
        // the record. 404 (not 403) — the beneficiary is intentionally cross-MDA here.
        $beneficiary = Beneficiary::query()
            ->withoutGlobalScope(MdaScope::class)
            ->findOrFail($request->input('beneficiary_id'));

        try {
            $serveRequest = $this->serveRequests->request($beneficiary, $user->mda_id, $user, $request->input('reason'));
        } catch (DomainException $e) {
            return ApiResponse::error('SERVE_REQUEST_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new ServeRequestResource($serveRequest))->resolve(), status: 201);
    }

    public function accept(DecideServeRequestRequest $request, string $serveRequest): JsonResponse
    {
        return $this->decide($request, $serveRequest, accept: true);
    }

    public function decline(DecideServeRequestRequest $request, string $serveRequest): JsonResponse
    {
        return $this->decide($request, $serveRequest, accept: false);
    }

    private function decide(DecideServeRequestRequest $request, string $serveRequestId, bool $accept): JsonResponse
    {
        $model = ServeRequest::query()->findOrFail($serveRequestId);
        $this->authorize('decide', $model);

        try {
            $accept
                ? $this->serveRequests->accept($model, $request->user(), $request->input('reason'))
                : $this->serveRequests->decline($model, $request->user(), $request->input('reason'));
        } catch (DomainException $e) {
            return ApiResponse::error('SERVE_REQUEST_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new ServeRequestResource($model->fresh()))->resolve());
    }
}
