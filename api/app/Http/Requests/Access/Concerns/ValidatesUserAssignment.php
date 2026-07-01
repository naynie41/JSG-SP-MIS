<?php

declare(strict_types=1);

namespace App\Http\Requests\Access\Concerns;

use App\Domain\Access\Models\Role;
use Closure;

/**
 * Validation rules that keep user creation/editing within the actor's authority
 * (SECURITY.md, least privilege): an actor may only place users in MDAs they can
 * access, and may only assign roles whose permissions they themselves hold (no
 * privilege escalation). Holders of cross-mda.view bypass both.
 */
trait ValidatesUserAssignment
{
    protected function accessibleMdaRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            $actor = $this->user();

            if ($value === null || $actor === null || $actor->canAccessAllMdas()) {
                return;
            }

            if (! in_array($value, $actor->accessibleMdaIds(), true)) {
                $fail('You can only assign users to an MDA you have access to.');
            }
        };
    }

    protected function assignableRoleRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            $actor = $this->user();

            if ($actor === null || $actor->canAccessAllMdas()) {
                return;
            }

            $role = Role::with('permissions')->find($value);

            if ($role === null) {
                return; // the exists rule reports a missing role
            }

            $extraPermissions = array_diff(
                $role->permissions->pluck('key')->all(),
                $actor->permissionKeys(),
            );

            if ($extraPermissions !== []) {
                $fail('You cannot assign a role with more permissions than your own.');
            }
        };
    }
}
