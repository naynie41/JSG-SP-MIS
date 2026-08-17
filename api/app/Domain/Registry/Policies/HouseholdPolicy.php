<?php

declare(strict_types=1);

namespace App\Domain\Registry\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\Household;

/**
 * Authorization for households (PRD FR-REG-01, §9).
 *
 * Mirrors the beneficiary rule: only the owner MDA may mutate a household or its
 * membership; oversight (`cross-mda.view`) grants read only. Membership changes
 * (add/move/remove, head designation) are authorized as household edits.
 */
class HouseholdPolicy
{
    private function owns(User $user, Household $household): bool
    {
        return $user->mda_id !== null && $user->mda_id === $household->owner_mda_id;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('household.view');
    }

    public function view(User $user, Household $household): bool
    {
        return $user->hasPermission('household.view')
            && ($this->owns($user, $household) || $user->canAccessAllMdas());
    }

    /*
     * No create() ability: households are formed by source ingestion
     * (HouseholdIngestionService), never by a create action, and there is no route that
     * would authorize one. A policy method for a capability that does not exist invites
     * someone to wire an endpoint to it.
     */

    /** Owner-only edit (incl. membership changes and head designation). */
    public function update(User $user, Household $household): bool
    {
        return $user->hasPermission('household.edit') && $this->owns($user, $household);
    }

    public function delete(User $user, Household $household): bool
    {
        return $this->update($user, $household);
    }
}
