<?php

declare(strict_types=1);

namespace App\Domain\Programme\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Programme;

/**
 * Authorization for programmes (PRD FR-PRG-01). The core rule: **only the owner
 * MDA may mutate** (create/update/archive). Oversight roles (`cross-mda.view`)
 * read across all MDAs but never mutate another MDA's programme.
 */
class ProgrammePolicy
{
    private function owns(User $user, Programme $programme): bool
    {
        return $user->mda_id !== null && $user->mda_id === $programme->owner_mda_id;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('programme.view');
    }

    public function view(User $user, Programme $programme): bool
    {
        return $user->hasPermission('programme.view')
            && ($this->owns($user, $programme) || $user->canAccessAllMdas());
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('programme.create');
    }

    /** Owner-only edit/archive. cross-mda.view does NOT grant mutation. */
    public function update(User $user, Programme $programme): bool
    {
        return $user->hasPermission('programme.edit') && $this->owns($user, $programme);
    }
}
