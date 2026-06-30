<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Authorizes a request against one or more `module.action` permissions
 * (PRD FR-UAM-05). Access is denied by default: the user must hold at least one
 * of the declared permissions, otherwise a 403 is returned in the standard
 * error envelope. Authorization is enforced server-side (SECURITY.md).
 *
 * Usage: ->middleware('permission:user.view') or 'permission:role.view,role.edit'
 */
class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if ($user !== null) {
            foreach ($permissions as $permission) {
                if ($user->hasPermission($permission)) {
                    return $next($request);
                }
            }
        }

        return ApiResponse::error(
            'FORBIDDEN',
            'You do not have permission to perform this action.',
            [],
            403,
        );
    }
}
