<?php

declare(strict_types=1);

namespace App\Domain\Programme\Policies;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Programme;

/**
 * Authorization for the programme catalog (PRD §10, ARCH §12.4, revised).
 *
 * Two kinds of entry, two rules:
 *
 *  - **Central catalog** (unowned) — created and edited by catalog administrators
 *    only: the System Administrator and SP Coordination. Readable by everyone.
 *  - **MDA-owned** — an MDA creates a programme for itself. Which MDAs may SEE it is
 *    settled by the MDA scope on the model, not here; this policy settles who may
 *    change it: the owning MDA while the decision is still open (pending or sent
 *    back), and nobody in the MDA once it is approved — an approved programme is a
 *    decided thing, and editing it would quietly undo the decision.
 *
 * Approving is the System Administrator's alone (a stakeholder decision, not ours
 * to widen): SP Coordination administers the central catalog but does not decide
 * another MDA's submissions.
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

    /** The MDA that owns this programme is the caller's own. */
    private function owns(User $user, Programme $programme): bool
    {
        return $programme->owner_mda_id !== null
            && $user->mda_id !== null
            && $programme->owner_mda_id === $user->mda_id;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('programme.view');
    }

    public function view(User $user, Programme $programme): bool
    {
        return $user->hasPermission('programme.view');
    }

    /**
     * Catalog admins create central entries; any user with the permission (the MDA
     * Admin) creates one owned by their own MDA. The controller decides which,
     * from the caller's role — the client never names the owner.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('programme.create')
            && ($this->isCatalogAdmin($user) || $user->mda_id !== null);
    }

    /**
     * Edit the entry. Catalog admins may edit any; an MDA may edit only its own,
     * and only while it is pending or has been sent back.
     */
    public function update(User $user, Programme $programme): bool
    {
        if ($this->isCatalogAdmin($user)) {
            return $user->hasPermission('programme.edit');
        }

        return $user->hasPermission('programme.edit')
            && $this->owns($user, $programme)
            && ! $programme->isApproved();
    }

    /** Re-submit after a rejection — the owning MDA, on its own programme. */
    public function submit(User $user, Programme $programme): bool
    {
        return $user->hasPermission('programme.edit')
            && $this->owns($user, $programme)
            && ! $programme->isApproved();
    }

    /**
     * Decide an MDA's submission. System Administrator only, and never on a central
     * entry — there is nothing to decide about one.
     */
    public function decide(User $user, Programme $programme): bool
    {
        return $user->hasPermission('programme.approve')
            && $user->role?->key === RoleKey::SystemAdministrator->value
            && ! $programme->isCentral();
    }
}
