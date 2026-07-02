<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\OwnershipTransferRequest;
use App\Domain\Registry\Services\OwnershipTransferService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\DecideOwnershipTransferRequest;
use App\Http\Requests\Registry\StoreOwnershipTransferRequest;
use App\Http\Resources\OwnershipTransferResource;
use App\Support\ApiResponse;
use DomainException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;

/**
 * Ownership transfer workflow endpoints (PRD FR-OWN-05).
 */
class OwnershipTransferController extends Controller
{
    public function __construct(private readonly OwnershipTransferService $transfers) {}

    /** A non-owner MDA requests ownership of a beneficiary. */
    public function store(StoreOwnershipTransferRequest $request, string $beneficiary): JsonResponse
    {
        $model = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->findOrFail($beneficiary);
        $this->authorize('requestTransfer', $model);

        try {
            $transfer = $this->transfers->request(
                $model,
                (string) $request->user()->mda_id,
                $request->user(),
                $request->input('reason'),
            );
        } catch (UniqueConstraintViolationException) {
            return ApiResponse::error('TRANSFER_PENDING', 'A transfer request is already pending for this beneficiary.', [], 409);
        } catch (DomainException $e) {
            return ApiResponse::error('TRANSFER_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new OwnershipTransferResource($transfer))->resolve(), status: 201);
    }

    /** The current owner MDA approves a pending transfer. */
    public function approve(DecideOwnershipTransferRequest $request, string $transfer): JsonResponse
    {
        $model = OwnershipTransferRequest::findOrFail($transfer);
        $this->authorizeDecision($model);

        try {
            $this->transfers->approve($model, $request->user());
        } catch (DomainException $e) {
            return ApiResponse::error('TRANSFER_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new OwnershipTransferResource($model->fresh()))->resolve());
    }

    /** The current owner MDA rejects a pending transfer. */
    public function reject(DecideOwnershipTransferRequest $request, string $transfer): JsonResponse
    {
        $model = OwnershipTransferRequest::findOrFail($transfer);
        $this->authorizeDecision($model);

        try {
            $this->transfers->reject($model, $request->user(), $request->input('reason'));
        } catch (DomainException $e) {
            return ApiResponse::error('TRANSFER_INVALID', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new OwnershipTransferResource($model->fresh()))->resolve());
    }

    private function authorizeDecision(OwnershipTransferRequest $transfer): void
    {
        // Resolve the beneficiary without the owner scope so the decision is
        // authorized by the policy (owner-only), not silently 404'd.
        $beneficiary = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->findOrFail($transfer->beneficiary_id);
        $this->authorize('decideTransfer', $beneficiary);
    }
}
