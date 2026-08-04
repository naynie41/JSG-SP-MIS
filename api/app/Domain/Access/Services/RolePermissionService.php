<?php

declare(strict_types=1);

namespace App\Domain\Access\Services;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Export\BeneficiaryListExport;
use RuntimeException;

/**
 * Writes the role → permission matrix (FR-UAM-05). It edits the EXISTING
 * `role_permission` pivot that {@see User::permissionKeys()} reads, so a change takes
 * effect on the next request — there is no second permission store to drift.
 *
 * Two invariants from docs/SECURITY.md are enforced here, not merely in the UI:
 *
 *  1. **The System Administrator role is not editable.** It holds every registered
 *     permission implicitly (RolesAndPermissionsSeeder); allowing an edit would let an
 *     administrator lock every administrator out of the console.
 *  2. **`export.reveal_pii` is never bundled into a role.** Unmasking NIN/BVN stays a
 *     System-Administrator-only capability; granting it to a role is a Data Protection
 *     Officer decision under NDPA/NDPR, not a console toggle.
 *
 * Grants that SECURITY.md flags as DPO-reviewable (`beneficiary.export` to a junior
 * role) are permitted but recorded distinctly in the audit entry, so a periodic review
 * can find them.
 */
class RolePermissionService
{
    /** Never assignable to any role — see docs/SECURITY.md §3. */
    public const NEVER_ROLE_GRANTABLE = [BeneficiaryListExport::REVEAL_PERMISSION];

    /** Grants that carry a DPO/sign-off obligation; allowed, but flagged in the audit. */
    public const SENSITIVE = ['beneficiary.export', 'beneficiary.access_request', 'cross-mda.view'];

    public function __construct(private readonly AuditLogger $audit) {}

    /** Whether this role's permissions may be edited at all. */
    public function isEditable(Role $role): bool
    {
        return $role->key !== RoleKey::SystemAdministrator->value;
    }

    /**
     * Replace a role's permission set with `$keys`.
     *
     * @param  list<string>  $keys
     *
     * @throws RuntimeException when the role is locked or a key is not grantable
     */
    public function sync(Role $role, array $keys, User $actor): Role
    {
        if (! $this->isEditable($role)) {
            throw new RuntimeException('The System Administrator role holds every permission and cannot be edited.');
        }

        $keys = array_values(array_unique($keys));

        foreach ($keys as $key) {
            if (in_array($key, self::NEVER_ROLE_GRANTABLE, true)) {
                throw new RuntimeException(
                    "{$key} can never be granted to a role — it stays a System Administrator capability (SECURITY.md)."
                );
            }
        }

        /** @var array<string, string> $idsByKey */
        $idsByKey = Permission::query()->whereIn('key', $keys)->pluck('id', 'key')->all();

        $unknown = array_diff($keys, array_keys($idsByKey));
        if ($unknown !== []) {
            throw new RuntimeException('Unknown permission: '.implode(', ', $unknown).'.');
        }

        $before = $role->permissions()->pluck('key')->sort()->values()->all();
        $role->permissions()->sync(array_values($idsByKey));
        $role->load('permissions:id,key');
        $after = $role->permissions->pluck('key')->sort()->values()->all();

        $granted = array_values(array_diff($after, $before));
        $revoked = array_values(array_diff($before, $after));

        $this->audit->record(
            'role.permissions_updated',
            $role,
            before: ['permissions' => $before],
            after: [
                'permissions' => $after,
                'granted' => $granted,
                'revoked' => $revoked,
                // Surfaced separately so a periodic export-permission review (SECURITY.md
                // §3 "Governance") can find these without diffing every entry.
                'sensitive_granted' => array_values(array_intersect($granted, self::SENSITIVE)),
            ],
            actor: $actor,
        );

        return $role;
    }
}
