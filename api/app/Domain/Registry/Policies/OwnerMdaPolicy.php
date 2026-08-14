<?php

declare(strict_types=1);

namespace App\Domain\Registry\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryServiceGrant;
use App\Domain\Registry\Models\ServiceRequest;

/**
 * Authorization for the Service Request seam (PRD §12, FR-OWN-06). Either party
 * (requesting or owner MDA) may READ a request; only the OWNER MDA may
 * accept/decline it — the decision belongs to the MDA that owns the beneficiary.
 * Ownership is never changed by any of these actions.
 */
class OwnerMdaPolicy
{
    /** Read: the requesting MDA, the owner MDA, or an oversight (all-MDA) role. */
    public function view(User $user, ServiceRequest $request): bool
    {
        return $user->canAccessAllMdas()
            || ($user->mda_id !== null && in_array($user->mda_id, [$request->from_mda_id, $request->to_mda_id], true));
    }

    /** Accept/decline — the owner MDA only (with the approval permission). */
    public function decide(User $user, ServiceRequest $request): bool
    {
        return $user->hasPermission('beneficiary.approve')
            && $user->mda_id !== null
            && $user->mda_id === $request->to_mda_id;
    }

    /**
     * Revoke an open read grant (FR-OWN-07).
     *
     * Symmetry with `decide`: the MDA that opened the access closes it, using the same
     * `beneficiary.approve` permission. Authority is checked against the beneficiary's
     * CURRENT owner rather than the request's `to_mda_id` — were ownership ever
     * transferred (FR-OWN-05), the right to withdraw access has to follow the record,
     * not stay with the MDA that happened to grant it.
     *
     * A System Administrator holding `mda-access.edit` ("Revoke cross-MDA access") may
     * override, which is the same capability that revokes an administrative grant.
     */
    public function revoke(User $user, BeneficiaryServiceGrant $grant): bool
    {
        if ($user->hasPermission('mda-access.edit') && $user->canAccessAllMdas()) {
            return true;
        }

        return $user->hasPermission('beneficiary.approve')
            && $user->mda_id !== null
            && $user->mda_id === $grant->beneficiary()->withoutGlobalScopes()->value('owner_mda_id');
    }
}
