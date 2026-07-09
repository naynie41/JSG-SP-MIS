<?php

declare(strict_types=1);

namespace App\Domain\Programme\Policies;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Programme;

/**
 * Authorization for the GLOBAL programme catalog (PRD §10, ARCH §12.4). Programmes
 * are a shared, unowned catalog: **any authenticated role may list/show** them,
 * but **only catalog administrators may create/edit/archive** — the System
 * Administrator, optionally SP Coordination (the "AdminOnly" rule). MDAs run
 * programmes through their own activities and can never mutate the catalog.
 */
class ProgrammePolicy
{
    /** Catalog administrators: System Administrator + (optionally) SP Coordination. */
    private function isCatalogAdmin(User $user): bool
    {
        return in_array($user->role?->key, [
            RoleKey::SystemAdministrator->value,
            RoleKey::SpCoordination->value,
        ], true);
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('programme.view');
    }

    public function view(User $user, Programme $programme): bool
    {
        return $user->hasPermission('programme.view');
    }

    public function create(User $user): bool
    {
        return $this->isCatalogAdmin($user) && $user->hasPermission('programme.create');
    }

    /** Edit/archive the catalog entry — catalog administrators only. */
    public function update(User $user, Programme $programme): bool
    {
        return $this->isCatalogAdmin($user) && $user->hasPermission('programme.edit');
    }
}
