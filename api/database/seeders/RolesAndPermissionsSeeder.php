<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use Illuminate\Database\Seeder;

/**
 * Seeds the seven predefined roles (PRD FR-UAM-01) and the permissions for the
 * Access module, then assigns permission bundles to each role.
 *
 * Idempotent: safe to re-run. Other modules add their own permissions in their
 * own seeders as they are built, then extend role assignments as needed. The
 * bundles below are sensible starting points and will be refined per phase.
 */
class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Permissions available so far (Access module + the cross-MDA bypass).
     * Format: key => [module, action, description].
     *
     * @var array<string, array{0: string, 1: string, 2: string}>
     */
    private const PERMISSIONS = [
        'mda.view' => ['mda', 'view', 'View MDAs'],
        'mda.create' => ['mda', 'create', 'Create MDAs'],
        'mda.edit' => ['mda', 'edit', 'Edit MDAs'],
        'user.view' => ['user', 'view', 'View users'],
        'user.create' => ['user', 'create', 'Create users'],
        'user.edit' => ['user', 'edit', 'Edit users'],
        'role.view' => ['role', 'view', 'View roles'],
        'role.edit' => ['role', 'edit', 'Edit roles and their permissions'],
        'permission.view' => ['permission', 'view', 'View permissions'],
        'cross-mda.view' => ['cross-mda', 'view', 'Access data across all MDAs (bypasses MDA scoping)'],
    ];

    /**
     * Role => list of permission keys. RoleKey::SystemAdministrator receives
     * every permission and is handled separately.
     *
     * @var array<string, list<string>>
     */
    private const ROLE_PERMISSIONS = [
        RoleKey::SpCoordination->value => [
            'cross-mda.view', 'mda.view', 'user.view', 'role.view', 'permission.view',
        ],
        RoleKey::MneOfficer->value => [
            'cross-mda.view', 'mda.view', 'user.view',
        ],
        RoleKey::MdaAdmin->value => [
            'mda.view', 'user.view', 'user.create', 'user.edit', 'role.view',
        ],
        RoleKey::MdaOfficer->value => [
            'mda.view', 'user.view',
        ],
        RoleKey::DevelopmentPartner->value => [
            'mda.view',
        ],
        RoleKey::Executive->value => [
            'cross-mda.view', 'mda.view', 'user.view',
        ],
    ];

    public function run(): void
    {
        $permissions = $this->seedPermissions();
        $this->seedRoles($permissions);
    }

    /**
     * @return array<string, Permission> keyed by permission key
     */
    private function seedPermissions(): array
    {
        $permissions = [];

        foreach (self::PERMISSIONS as $key => [$module, $action, $description]) {
            $permissions[$key] = Permission::updateOrCreate(
                ['key' => $key],
                ['module' => $module, 'action' => $action, 'description' => $description],
            );
        }

        return $permissions;
    }

    /**
     * @param  array<string, Permission>  $permissions
     */
    private function seedRoles(array $permissions): void
    {
        // Roles for which MFA is mandatory (PRD FR-UAM-04).
        $mfaRequiredRoles = [
            RoleKey::SystemAdministrator->value,
            RoleKey::Executive->value,
        ];

        foreach (RoleKey::cases() as $roleKey) {
            $role = Role::updateOrCreate(
                ['key' => $roleKey->value],
                [
                    'name' => $roleKey->label(),
                    'is_system' => true,
                    'requires_mfa' => in_array($roleKey->value, $mfaRequiredRoles, true),
                ],
            );

            $keys = $roleKey === RoleKey::SystemAdministrator
                ? array_keys(self::PERMISSIONS)
                : (self::ROLE_PERMISSIONS[$roleKey->value] ?? []);

            $ids = array_map(static fn (string $key) => $permissions[$key]->id, $keys);

            $role->permissions()->sync($ids);
        }
    }
}
