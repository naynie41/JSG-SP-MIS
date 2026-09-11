<?php

declare(strict_types=1);

namespace App\Http\Requests\Access\Concerns;

use App\Domain\Access\Models\Role;
use Closure;
use Illuminate\Contracts\Validation\Validator;

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

    /**
     * The role decides whether an MDA is required (FR-UAM-02/03).
     *
     * An MDA Admin operates one MDA's workspace, so it must have one. State-level
     * roles — Executive, SP Coordination, M&E Officer, Development Partner, System
     * Administrator — must NOT, or their MDA scope would silently narrow everything
     * they are meant to see across.
     *
     * Previously `mda_id` was required or nullable based on the ACTOR's permissions,
     * which meant a System Administrator (holding cross-mda.view) could create an MDA
     * Admin with no MDA at all, and could pin an Executive to one. The rule belongs to
     * the role being ASSIGNED, not to the person assigning it.
     *
     * Reads `roles.requires_mda` rather than naming roles here, so this and the
     * client's dropdown are driven by one value.
     *
     * Run from `withValidator`, NOT as a rule on `mda_id`. Laravel skips rules for an
     * attribute that is absent from the payload, so a create request that simply
     * omits `mda_id` would pass a rule-based check — which is precisely the case that
     * must fail for an MDA Admin.
     *
     * @param  string|null  $roleId  the effective role; null means "nothing to check"
     * @param  mixed  $mdaId  the effective MDA id
     */
    protected function assertMdaMatchesRole(Validator $validator, ?string $roleId, mixed $mdaId): void
    {
        if ($roleId === null || $roleId === '') {
            return;
        }

        $role = Role::find($roleId);

        if ($role === null) {
            return; // the exists rule on role_id reports this
        }

        $hasMda = $mdaId !== null && $mdaId !== '';

        if ($role->requires_mda && ! $hasMda) {
            $validator->errors()->add(
                'mda_id',
                "The {$role->name} role is scoped to one MDA — select the MDA this user belongs to.",
            );

            return;
        }

        if (! $role->requires_mda && $hasMda) {
            $validator->errors()->add(
                'mda_id',
                "The {$role->name} role works across all MDAs and cannot be assigned to one.",
            );
        }
    }
}
