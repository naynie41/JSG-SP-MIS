<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Registry;

use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Services\BeneficiaryRegistrar;
use App\Http\Controllers\Controller;
use App\Http\Requests\Registry\ApiRegistrationRequest;
use App\Http\Resources\BeneficiaryResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

/**
 * Inbound REST registration intake (PRD FR-REG-02, §6.1). An authenticated,
 * rate-limited endpoint that lets an integrating system submit ONE beneficiary
 * per call. It runs the shared validation and stamps provenance
 * (source = "api", original_record_id from the caller, owner = caller's MDA), so
 * the record is fully traceable. See docs/registry-intake.md.
 */
class BeneficiaryIntakeController extends Controller
{
    public function store(ApiRegistrationRequest $request, BeneficiaryRegistrar $registrar): JsonResponse
    {
        $this->authorize('create', Beneficiary::class);

        $mdaId = $request->user()->mda_id;
        if ($mdaId === null) {
            return ApiResponse::error('MDA_REQUIRED', 'Only users assigned to an MDA can register beneficiaries.', [], 422);
        }

        $data = $request->validated();

        // The caller's own record id is the natural idempotency key, so repeat
        // submissions (e.g. an offline client retrying) don't duplicate (FR-REG-08).
        $idempotencyKey = $data['idempotency_key'] ?? $data['original_record_id'];

        $beneficiary = $registrar->register(
            Arr::except($data, ['original_record_id', 'idempotency_key']),
            $mdaId,
            RegistrationSource::Api,
            $data['original_record_id'],
            null,
            $idempotencyKey,
        );

        return ApiResponse::success(
            (new BeneficiaryResource($beneficiary->load('ownerMda')))->resolve(),
            status: $beneficiary->wasRecentlyCreated ? 201 : 200,
        );
    }
}
