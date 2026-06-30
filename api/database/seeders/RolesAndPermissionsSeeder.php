<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Services\PermissionSynchronizer;
use Illuminate\Database\Seeder;

/**
 * Persists the registered module permissions (via PermissionSynchronizer), seeds
 * the seven predefined roles (PRD FR-UAM-01), and assigns each role its
 * permission bundle. Idempotent: safe to re-run.
 *
 * Permissions themselves are declared by each module in its service provider
 * (see PermissionRegistry); this seeder only owns the role → permission mapping.
 */
class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Role => permission keys. System Administrator implicitly receives every
     * registered permission and is handled separately.
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

    public function run(PermissionSynchronizer $synchronizer): void
    {
        $synchronizer->sync();

        $this->seedRoles();
    }

    private function seedRoles(): void
    {
        // Permission id lookup, keyed by permission key.
        $permissionIds = Permission::pluck('id', 'key');
        $allKeys = $permissionIds->keys()->all();

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
                ? $allKeys
                : (self::ROLE_PERMISSIONS[$roleKey->value] ?? []);

            $role->permissions()->sync(
                collect($keys)->map(fn (string $key) => $permissionIds[$key])->filter()->all()
            );
        }
    }
}
