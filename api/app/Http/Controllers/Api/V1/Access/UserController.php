<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Access;

use App\Domain\Access\Models\User;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Lists users. This is the representative consumer of MDA data-scoping: the
 * result is automatically restricted to the caller's accessible MDAs by the
 * User model's global scope (no scoping logic lives here) — SECURITY.md §3.
 */
class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::query()
            ->with('mda:id,name')
            ->orderBy('name')
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status->value,
                'mda' => $user->mda ? ['id' => $user->mda->id, 'name' => $user->mda->name] : null,
            ]);

        return ApiResponse::success(['users' => $users]);
    }
}
