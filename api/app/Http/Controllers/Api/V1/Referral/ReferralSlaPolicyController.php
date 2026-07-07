<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Referral;

use App\Domain\Referral\Enums\ReferralStatus;
use App\Domain\Referral\Models\ReferralSlaPolicy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Referral\UpdateReferralSlaRequest;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Admin management of referral SLA windows (PRD FR-REF-04/05). Global config —
 * gated by `referral-sla.edit`. One window (hours) per referral status; edits are
 * audited via the model's Auditable trait.
 */
class ReferralSlaPolicyController extends Controller
{
    public function index(): JsonResponse
    {
        $policies = ReferralSlaPolicy::query()->orderBy('status')->get()
            ->map(fn (ReferralSlaPolicy $p): array => ['status' => $p->status, 'hours' => $p->hours])
            ->all();

        return ApiResponse::success(['sla_policies' => $policies]);
    }

    /** Upsert the SLA window (hours) for a status. */
    public function update(UpdateReferralSlaRequest $request, string $status): JsonResponse
    {
        $statusEnum = ReferralStatus::tryFrom($status);
        if ($statusEnum === null) {
            return ApiResponse::error('INVALID_STATUS', 'Unknown referral status.', [], 422);
        }

        $policy = ReferralSlaPolicy::query()->updateOrCreate(
            ['status' => $statusEnum->value],
            ['hours' => $request->integer('hours')],
        );

        return ApiResponse::success(['status' => $policy->status, 'hours' => $policy->hours]);
    }
}
