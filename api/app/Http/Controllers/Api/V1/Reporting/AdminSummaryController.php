<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Reporting;

use App\Domain\Reporting\Services\AdminSummaryService;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Governance summary for the System Administrator console (FR-UAM-01, FR-AUD-01).
 * Returns provisioning/catalog KPIs, user adoption, the registry data-quality
 * snapshot, administrative alerts and recent audit activity.
 *
 * Gated to the System Administrator ROLE (not a permission): the console is
 * governance territory, and a System Administrator implicitly holds every
 * permission, so no permission is exclusive to them. De-identified counts only —
 * no beneficiary PII and no audit before/after payloads.
 */
class AdminSummaryController extends Controller
{
    public function __construct(private readonly AdminSummaryService $summary) {}

    public function show(): JsonResponse
    {
        return ApiResponse::success($this->summary->build());
    }
}
