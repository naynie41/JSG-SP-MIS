<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Access;

use App\Domain\Access\Services\LoginActivityService;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Login activity for the administration console (FR-UAM-04/06, FR-AUD-01) — a read-only
 * projection of the existing audit trail, never a second log. Gated to the System
 * Administrator role, like the rest of the console.
 */
class LoginActivityController extends Controller
{
    public function __construct(private readonly LoginActivityService $activity) {}

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['nullable', 'uuid'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        return ApiResponse::success($this->activity->recent(
            $validated['user_id'] ?? null,
            (int) ($validated['limit'] ?? 50),
        ));
    }
}
