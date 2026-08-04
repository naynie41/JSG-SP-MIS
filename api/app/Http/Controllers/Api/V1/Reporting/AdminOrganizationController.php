<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Reporting;

use App\Domain\Reporting\Services\AdminOrganizationService;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Organization roll-up for the administration console (FR-UAM-01, FR-PRG-05):
 * per-MDA user allocation, MDA administrators and owned activities, plus the
 * Development Partners and the delivery they fund.
 *
 * READ ONLY — organizations are created, edited, activated and deactivated through
 * the existing `/mdas` endpoints and their policies; this endpoint never duplicates
 * that logic. Gated to the System Administrator role, like the rest of the console.
 */
class AdminOrganizationController extends Controller
{
    public function __construct(private readonly AdminOrganizationService $organizations) {}

    public function index(): JsonResponse
    {
        return ApiResponse::success($this->organizations->build());
    }
}
