<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Reporting;

use App\Domain\Reporting\Services\AdminSettingsService;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * The console's Settings projection: the EFFECTIVE configuration in force, read-only.
 * See {@see AdminSettingsService} — the console keeps no settings store of its own.
 */
class AdminSettingsController extends Controller
{
    public function __construct(private readonly AdminSettingsService $settings) {}

    public function index(): JsonResponse
    {
        return ApiResponse::success($this->settings->all());
    }
}
