<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Access;

use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * Read-only administration views over the RBAC model (PRD FR-UAM-05): the
 * permission catalogue, the roles, and the role × permission matrix. Each
 * action is guarded by an explicit permission (see routes/api.php).
 */
class AccessController extends Controller
{
    /**
     * The permission catalogue, grouped by module.
     */
    public function permissions(): JsonResponse
    {
        $grouped = Permission::query()
            ->orderBy('module')
            ->orderBy('action')
            ->get()
            ->groupBy('module')
            ->map(fn ($items) => $items->map(fn (Permission $p) => [
                'key' => $p->key,
                'action' => $p->action->value,
                'description' => $p->description,
            ])->values());

        return ApiResponse::success(['modules' => $grouped]);
    }

    /**
     * All roles with the permission keys they grant.
     */
    public function roles(): JsonResponse
    {
        $roles = Role::query()
            ->with('permissions:id,key')
            ->orderBy('name')
            ->get()
            ->map(fn (Role $role) => [
                'id' => $role->id,
                'key' => $role->key,
                'name' => $role->name,
                'description' => $role->description,
                'requires_mfa' => $role->requires_mfa,
                'permissions' => $role->permissions->pluck('key')->values(),
            ]);

        return ApiResponse::success(['roles' => $roles]);
    }

    /**
     * The role × permission matrix: the full permission key list plus, for each
     * role, the keys it holds. Lets an admin see exactly who can do what.
     */
    public function matrix(): JsonResponse
    {
        $permissionKeys = Permission::query()
            ->orderBy('module')
            ->orderBy('action')
            ->pluck('key')
            ->values();

        $roles = Role::query()
            ->with('permissions:id,key')
            ->orderBy('name')
            ->get()
            ->map(fn (Role $role) => [
                'key' => $role->key,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('key')->values(),
            ]);

        return ApiResponse::success([
            'permissions' => $permissionKeys,
            'roles' => $roles,
        ]);
    }
}
