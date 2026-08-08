<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Reporting;

use App\Domain\Access\Models\User;
use App\Domain\Reporting\Services\MdaActionRequiredService;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Live "what needs me today" counters for the MDA console's Overview. Counts only —
 * see {@see MdaActionRequiredService} for why these sit outside the Phase 6 snapshot.
 */
class MdaActionRequiredController extends Controller
{
    public function __construct(private readonly MdaActionRequiredService $actions) {}

    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        return ApiResponse::success($this->actions->forUser($user));
    }
}
