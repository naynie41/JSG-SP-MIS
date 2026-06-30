<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enforces the session idle timeout (SECURITY.md §2). A token that has not been
 * used within the configured window is revoked and rejected.
 *
 * Runs BEFORE auth:sanctum so it reads the token's previous last_used_at, before
 * Sanctum updates it to "now" for the current request.
 */
class EnforceIdleTimeout
{
    public function handle(Request $request, Closure $next): Response
    {
        $bearer = $request->bearerToken();

        if ($bearer !== null) {
            $token = PersonalAccessToken::findToken($bearer);

            if ($token !== null && $this->isIdle($token)) {
                $token->delete();

                return ApiResponse::error('SESSION_EXPIRED', 'Session expired due to inactivity.', [], 401);
            }
        }

        // No/invalid token: let auth:sanctum produce the standard 401.
        return $next($request);
    }

    private function isIdle(PersonalAccessToken $token): bool
    {
        $idleMinutes = (int) config('security.session.idle_timeout_minutes');

        if ($idleMinutes <= 0 || $token->last_used_at === null) {
            return false;
        }

        return $token->last_used_at->lt(now()->subMinutes($idleMinutes));
    }
}
