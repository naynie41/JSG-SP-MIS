<?php

declare(strict_types=1);

namespace App\Http\Controllers\Concerns;

use App\Domain\Access\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Shared auth error responses so login and the MFA challenge stay consistent.
 */
trait AuthResponses
{
    /**
     * Generic credential failure — does not reveal whether the account exists
     * (SECURITY.md §2).
     */
    protected function invalidCredentials(): JsonResponse
    {
        return ApiResponse::error('INVALID_CREDENTIALS', 'Invalid credentials.', [], 401);
    }

    /**
     * Account temporarily locked after too many failed attempts (FR-UAM-06).
     * Includes a Retry-After header with the seconds until the lock clears.
     */
    protected function accountLocked(User $user): JsonResponse
    {
        $response = ApiResponse::error(
            'ACCOUNT_LOCKED',
            'Your account is temporarily locked due to multiple failed login attempts. Please try again later.',
            [],
            423,
        );

        if ($user->locked_until !== null) {
            $retryAfter = max(0, $user->locked_until->getTimestamp() - now()->getTimestamp());
            $response->headers->set('Retry-After', (string) $retryAfter);
        }

        return $response;
    }
}
