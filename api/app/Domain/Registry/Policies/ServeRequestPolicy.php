<?php

declare(strict_types=1);

namespace App\Domain\Registry\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\ServeRequest;

/**
 * Authorization for request-to-serve (PRD FR-DUP-05). Either party (requesting or
 * owner MDA) may read a request; only the OWNER MDA may accept/decline it (using
 * the same approval permission as ownership transfers).
 */
class ServeRequestPolicy
{
    public function view(User $user, ServeRequest $request): bool
    {
        return $user->canAccessAllMdas()
            || ($user->mda_id !== null && in_array($user->mda_id, [$request->from_mda_id, $request->to_mda_id], true));
    }

    /** Accept/decline — owner MDA only. */
    public function decide(User $user, ServeRequest $request): bool
    {
        return $user->hasPermission('beneficiary.approve')
            && $user->mda_id !== null
            && $user->mda_id === $request->to_mda_id;
    }
}
