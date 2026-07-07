<?php

declare(strict_types=1);

namespace App\Domain\Referral\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Referral\Models\Referral;

/**
 * Authorization for referrals (PRD FR-REF). Visibility is handled by the two-party
 * scope; here we gate the WHO of each action: the **receiving** MDA drives
 * accept/reject/request-info + the working states, and the **originating** MDA
 * responds to a more-info request. Oversight (`cross-mda.view`) reads only.
 */
class ReferralPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('referral.view');
    }

    public function view(User $user, Referral $referral): bool
    {
        return $user->hasPermission('referral.view')
            && ($user->canAccessAllMdas() || $this->isParty($user, $referral));
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('referral.create') && $user->mda_id !== null;
    }

    /** Accept / reject / request-info / start / complete / close — receiving MDA only. */
    public function receive(User $user, Referral $referral): bool
    {
        return $user->hasPermission('referral.edit')
            && $user->mda_id !== null
            && $user->mda_id === $referral->to_mda_id;
    }

    /** Respond to a more-info request — originating MDA only. */
    public function originate(User $user, Referral $referral): bool
    {
        return $user->hasPermission('referral.edit')
            && $user->mda_id !== null
            && $user->mda_id === $referral->from_mda_id;
    }

    private function isParty(User $user, Referral $referral): bool
    {
        return $user->mda_id !== null
            && in_array($user->mda_id, [$referral->from_mda_id, $referral->to_mda_id], true);
    }
}
