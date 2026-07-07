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
            'beneficiary.view', 'beneficiary.export', 'beneficiary-lookup.view',
            'programme.view', 'activity.view', 'enrollment.view', 'benefit.view',
            'double-dipping.view', 'double-dipping.edit', 'referral.view', 'referral-sla.edit',
            'grievance.view', 'grievance-sla.edit',
        ],
        RoleKey::MneOfficer->value => [
            'cross-mda.view', 'mda.view', 'user.view',
            'beneficiary.view', 'beneficiary.export', 'beneficiary-lookup.view',
            'programme.view', 'activity.view', 'enrollment.view', 'benefit.view', 'referral.view', 'grievance.view',
        ],
        RoleKey::MdaAdmin->value => [
            'mda.view', 'user.view', 'user.create', 'user.edit', 'role.view',
            'beneficiary.view', 'beneficiary.create', 'beneficiary.edit', 'beneficiary.approve',
            'beneficiary-lookup.view', 'household.view', 'household.create', 'household.edit',
            'programme.view', 'programme.create', 'programme.edit',
            'activity.view', 'activity.create', 'activity.edit',
            'enrollment.view', 'enrollment.create', 'enrollment.edit',
            'benefit.view', 'benefit.create', 'benefit.approve',
            'referral.view', 'referral.create', 'referral.edit',
            'grievance.view', 'grievance.create', 'grievance.edit',
        ],
        RoleKey::MdaOfficer->value => [
            'mda.view', 'user.view',
            'beneficiary.view', 'beneficiary.create', 'beneficiary.edit',
            'beneficiary-lookup.view', 'household.view', 'household.create', 'household.edit',
            'programme.view', 'programme.create', 'programme.edit',
            'activity.view', 'activity.create', 'activity.edit',
            'enrollment.view', 'enrollment.create', 'enrollment.edit',
            'benefit.view', 'benefit.create', 'benefit.approve',
            'referral.view', 'referral.create', 'referral.edit',
            'grievance.view', 'grievance.create', 'grievance.edit',
        ],
        RoleKey::DevelopmentPartner->value => [
            'mda.view',
            'beneficiary.view',
            'programme.view', 'activity.view', 'enrollment.view', 'benefit.view',
        ],
        RoleKey::Executive->value => [
            'cross-mda.view', 'mda.view', 'user.view',
            'beneficiary.view', 'beneficiary.export', 'beneficiary-lookup.view',
            'programme.view', 'activity.view', 'enrollment.view', 'benefit.view', 'referral.view', 'grievance.view',
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
