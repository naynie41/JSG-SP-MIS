<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks a user carrying `must_change_password` from doing anything except
 * changing it (SECURITY.md §2, FR-UAM-06).
 *
 * Registered GLOBALLY on the api group with a small named allow-list, rather than
 * being added group by group in routes/api.php. That direction is deliberate: a
 * route added later is protected by DEFAULT, and forgetting to opt in cannot
 * silently leave a hole. It matches the deny-by-default posture of the
 * `permission` middleware and the scope-bypass allow-list.
 *
 * Reads the bearer token directly, the way EnforceIdleTimeout does, because the
 * default guard is `web` (session) — $request->user() would resolve the wrong
 * guard, and global middleware runs before the route's auth:sanctum anyway.
 */
class RequirePasswordChange
{
    /**
     * Routes reachable while a password change is outstanding.
     *
     * The MFA routes MUST be here. A user whose role mandates MFA receives a SETUP
     * token from login, not a full one; blocking enrolment would deadlock them
     * between "enrol MFA first" and "change password first" with no way out.
     *
     * @var list<string>
     */
    private const ALLOWED_ROUTES = [
        'health',
        'auth.login',
        'auth.logout',
        'auth.me',
        'auth.password',
        'auth.mfa.challenge',
        'auth.mfa.enroll',
        'auth.mfa.verify',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (in_array($request->route()?->getName(), self::ALLOWED_ROUTES, true)) {
            return $next($request);
        }

        $bearer = $request->bearerToken();

        if ($bearer === null) {
            return $next($request);
        }

        $token = PersonalAccessToken::findToken($bearer);
        $user = $token?->tokenable;

        if ($user !== null && (bool) $user->getAttribute('must_change_password') === true) {
            return ApiResponse::error(
                'PASSWORD_CHANGE_REQUIRED',
                'Your password must be changed before you can continue.',
                [],
                403,
            );
        }

        return $next($request);
    }
}
