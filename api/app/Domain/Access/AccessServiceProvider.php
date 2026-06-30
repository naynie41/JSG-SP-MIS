<?php

declare(strict_types=1);

namespace App\Domain\Access;

use App\Domain\Access\Enums\PermissionAction;
use App\Domain\Access\Models\User;
use App\Domain\Access\Support\PermissionRegistry;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Access domain: the permission registry, this module's permissions,
 * and the Gate bridge that resolves any `module.action` ability against the
 * authenticated user's role permissions (authorization is server-side and
 * deny-by-default, SECURITY.md).
 */
class AccessServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PermissionRegistry::class);
    }

    public function boot(): void
    {
        $this->registerPermissions($this->app->make(PermissionRegistry::class));
        $this->registerGate();
    }

    /**
     * Permissions owned by the Access module. Other modules register their own
     * the same way (resolve PermissionRegistry in their provider and ->register).
     */
    private function registerPermissions(PermissionRegistry $registry): void
    {
        $registry
            ->register('user', PermissionAction::View, 'View users')
            ->register('user', PermissionAction::Create, 'Create users')
            ->register('user', PermissionAction::Edit, 'Edit users')
            ->register('mda', PermissionAction::View, 'View MDAs')
            ->register('mda', PermissionAction::Create, 'Create MDAs')
            ->register('mda', PermissionAction::Edit, 'Edit MDAs')
            ->register('role', PermissionAction::View, 'View roles')
            ->register('role', PermissionAction::Edit, 'Edit roles and their permissions')
            ->register('permission', PermissionAction::View, 'View permissions')
            ->register('cross-mda', PermissionAction::View, 'Access data across all MDAs (bypasses MDA scoping)')
            ->register('mda-access', PermissionAction::View, 'View cross-MDA access grants')
            ->register('mda-access', PermissionAction::Create, 'Grant cross-MDA access')
            ->register('mda-access', PermissionAction::Edit, 'Revoke cross-MDA access');
    }

    /**
     * Resolve any ability shaped like a permission key against the user's
     * permissions. Returns true to allow, or null to fall through to other
     * gates/policies (and ultimately deny by default).
     */
    private function registerGate(): void
    {
        Gate::before(function (?User $user, string $ability): ?bool {
            if ($user instanceof User && $user->hasPermission($ability)) {
                return true;
            }

            return null;
        });
    }
}
