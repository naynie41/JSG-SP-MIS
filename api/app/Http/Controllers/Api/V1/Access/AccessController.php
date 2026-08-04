<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Access;

use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Services\RolePermissionService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Access\UpdateRolePermissionsRequest;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use RuntimeException;

/**
 * Administration views over the RBAC model (PRD FR-UAM-05): the permission catalogue,
 * the roles, and the role × permission matrix — plus the matrix WRITE that backs the
 * console's permission editor. Each action is guarded by an explicit permission (see
 * routes/api.php); the write additionally enforces the SECURITY.md invariants in
 * {@see RolePermissionService}.
 */
class AccessController extends Controller
{
    /**
     * The permission catalogue, grouped by module.
     */
    public function permissions(): JsonResponse
    {
        $permissions = Permission::query()->orderBy('module')->orderBy('action')->get();

        /** @var array<string, list<array{key: string, action: string, description: string|null}>> $grouped */
        $grouped = [];
        foreach ($permissions as $permission) {
            $grouped[$permission->module][] = [
                'key' => $permission->key,
                'action' => $permission->action->value,
                'description' => $permission->description,
            ];
        }

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
    public function matrix(RolePermissionService $service): JsonResponse
    {
        $permissions = Permission::query()
            ->orderBy('module')
            ->orderBy('action')
            ->get()
            ->map(fn (Permission $permission) => [
                'key' => $permission->key,
                'module' => $permission->module,
                'action' => $permission->action->value,
                'action_label' => $permission->action->label(),
                'description' => $permission->description,
                // Policy travels WITH the matrix so the editor renders the rule rather
                // than re-stating docs/SECURITY.md in the UI.
                'role_grantable' => ! in_array($permission->key, RolePermissionService::NEVER_ROLE_GRANTABLE, true),
                'sensitive' => in_array($permission->key, RolePermissionService::SENSITIVE, true),
            ]);

        $roles = Role::query()
            ->with('permissions:id,key')
            ->orderBy('name')
            ->get()
            ->map(fn (Role $role) => [
                'id' => $role->id,
                'key' => $role->key,
                'name' => $role->name,
                'editable' => $service->isEditable($role),
                'permissions' => $role->permissions->pluck('key')->values(),
            ]);

        return ApiResponse::success([
            'permissions' => $permissions,
            'roles' => $roles,
        ]);
    }

    /**
     * Replace a role's permission set (the console's matrix editor). Writes the
     * existing `role_permission` pivot, so the change takes effect on the next request
     * with no second store to keep in step. Audited as `role.permissions_updated`.
     */
    public function updatePermissions(
        UpdateRolePermissionsRequest $request,
        string $role,
        RolePermissionService $service,
    ): JsonResponse {
        $model = Role::query()->findOrFail($role);

        /** @var User $actor */
        $actor = $request->user();

        try {
            $service->sync($model, array_values($request->validated()['permissions']), $actor);
        } catch (RuntimeException $e) {
            return ApiResponse::error('PERMISSION_NOT_GRANTABLE', $e->getMessage(), [], 422);
        }

        return ApiResponse::success([
            'role' => [
                'id' => $model->id,
                'key' => $model->key,
                'name' => $model->name,
                'editable' => $service->isEditable($model),
                'permissions' => $model->permissions->pluck('key')->values(),
            ],
        ]);
    }
}
