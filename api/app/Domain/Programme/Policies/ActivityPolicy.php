<?php

declare(strict_types=1);

namespace App\Domain\Programme\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;

/**
 * Authorization for activities (PRD §10, ARCH §12.4, FR-PRG-02). An activity is
 * owned by the MDA that created it (`owner_mda_id`); only that MDA may mutate it.
 * Any MDA user may create an activity against a GLOBAL catalog programme — the new
 * activity is owned by their own MDA, so there is no programme-ownership check.
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

    /**
     * Create an activity that runs a catalog programme — any MDA user may, and it
     * becomes owned by their MDA. The parent programme is passed to confirm it
     * exists (a valid catalog reference); its (absent) ownership is irrelevant.
     */
    public function create(User $user, Programme $programme): bool
    {
        return $user->hasPermission('activity.create') && $user->mda_id !== null;
    }

    /** Owner-only edit/archive. */
    public function update(User $user, Activity $activity): bool
    {
        return $user->hasPermission('activity.edit') && $this->owns($user, $activity);
    }
}
