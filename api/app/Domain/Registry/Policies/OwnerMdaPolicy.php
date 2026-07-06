<?php

declare(strict_types=1);

namespace App\Domain\Registry\Policies;

use App\Domain\Access\Models\User;
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
}
