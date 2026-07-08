<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Reporting;

use App\Domain\Reporting\Services\DashboardService;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Dashboard read endpoint (PRD FR-RPT-01/02, FR-DSH-01). Returns the consolidated,
 * de-identified metric snapshot for the CALLER'S resolved scope — Executive/SP
 * Coordination state-wide, an MDA user their own MDA, a Development Partner their
 * funded programmes. Served from the summary table, not a raw scan.
 */
class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboard) {}

    public function index(Request $request): JsonResponse
    {
        return ApiResponse::success($this->dashboard->forUser($request->user()));
    }
}
