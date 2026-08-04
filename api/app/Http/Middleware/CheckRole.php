<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Authorizes a request against one or more ROLE keys (PRD FR-UAM-01/05).
 *
 * This is deliberately distinct from {@see CheckPermission}: a few surfaces are scoped
 * to a role as a whole rather than to a capability — notably the System Administrator
 * console, which is governance territory and must not open up merely because another
 * role happens to hold one of the underlying permissions (a System Administrator
 * implicitly holds every permission, so no permission is exclusive to them).
 *
 * Denied by default; a failure returns 403 in the standard error envelope.
 * Authorization is enforced server-side (SECURITY.md §3).
 *
 * Usage: ->middleware('role:system_administrator')
 */
class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $roleKey = $request->user()?->role?->key;

        if ($roleKey !== null && in_array($roleKey, $roles, true)) {
            return $next($request);
        }

        return ApiResponse::error(
            'FORBIDDEN',
            'You do not have permission to perform this action.',
            [],
            403,
        );
    }
}
