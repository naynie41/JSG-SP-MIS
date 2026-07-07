<?php

declare(strict_types=1);

namespace App\Domain\Grievance\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Grievance\Models\Grievance;

/**
 * Authorization for grievances (PRD FR-GRM-01/02). Visibility is the MDA scope
 * (only the handling MDA + oversight); acting (assign / transition) is restricted
 * to the handling MDA's staff. Oversight (`cross-mda.view`) reads only.
 */
class GrievancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('grievance.view');
    }

    public function view(User $user, Grievance $grievance): bool
    {
        return $user->hasPermission('grievance.view')
            && ($user->canAccessAllMdas() || $user->mda_id === $grievance->handling_mda_id);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('grievance.create') && $user->mda_id !== null;
    }

    /** Assign / start / resolve / close — the handling MDA's staff only. */
    public function manage(User $user, Grievance $grievance): bool
    {
        return $user->hasPermission('grievance.edit')
            && $user->mda_id !== null
            && $user->mda_id === $grievance->handling_mda_id;
    }
}
