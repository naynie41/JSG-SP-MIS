<?php

declare(strict_types=1);

namespace App\Domain\Programme\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;

/**
 * Authorization for activities (PRD FR-PRG-02). An activity belongs to the
 * programme owner's MDA (via the denormalised `owner_mda_id`); only that MDA may
 * mutate. Creating an activity is a mutation of an owned programme, so `create`
 * takes the parent programme and checks ownership.
 */
class ActivityPolicy
{
    private function owns(User $user, Activity $activity): bool
    {
        return $user->mda_id !== null && $user->mda_id === $activity->owner_mda_id;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('activity.view');
    }

    public function view(User $user, Activity $activity): bool
    {
        return $user->hasPermission('activity.view')
            && ($this->owns($user, $activity) || $user->canAccessAllMdas());
    }

    /** Create an activity under a programme — owner MDA of the programme only. */
    public function create(User $user, Programme $programme): bool
    {
        return $user->hasPermission('activity.create')
            && $user->mda_id !== null
            && $user->mda_id === $programme->owner_mda_id;
    }

    /** Owner-only edit/archive. */
    public function update(User $user, Activity $activity): bool
    {
        return $user->hasPermission('activity.edit') && $this->owns($user, $activity);
    }
}
