<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\BenefitFlag;

/**
 * Authorization for double-dipping flags (PRD FR-BEN-05). A flag involves two
 * MDAs; either of them, plus oversight, may see and review it.
 */
class BenefitFlagPolicy
{
    private function involves(User $user, BenefitFlag $flag): bool
    {
        return $user->mda_id !== null && in_array($user->mda_id, [$flag->from_mda_id, $flag->other_mda_id], true);
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('benefit.view');
    }

    public function view(User $user, BenefitFlag $flag): bool
    {
        return $user->hasPermission('benefit.view') && ($user->canAccessAllMdas() || $this->involves($user, $flag));
    }

    /** Review (confirm/dismiss) — an involved MDA or oversight. */
    public function review(User $user, BenefitFlag $flag): bool
    {
        return $user->hasPermission('benefit.approve') && ($user->canAccessAllMdas() || $this->involves($user, $flag));
    }
}
