<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\BenefitImportBatch;
use App\Domain\Programme\Models\Activity;

/**
 * Authorization for bulk benefit-delivery imports (PRD FR-BEN-02, §8.3). Upload and
 * commit are done by the delivering MDA (which owns the activity/programme); reads
 * are scoped to that MDA plus oversight.
 */
class BenefitImportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('benefit.view');
    }

    public function view(User $user, BenefitImportBatch $batch): bool
    {
        return $user->hasPermission('benefit.view')
            && ($user->mda_id === $batch->mda_id || $user->canAccessAllMdas());
    }

    /** Upload a delivery list for an activity — the activity's owner MDA only. */
    public function create(User $user, Activity $activity): bool
    {
        return $user->hasPermission('benefit.create')
            && $user->mda_id !== null
            && $user->mda_id === $activity->owner_mda_id;
    }

    /** Confirm/commit a batch — the importing MDA only. */
    public function commit(User $user, BenefitImportBatch $batch): bool
    {
        return $user->hasPermission('benefit.create') && $user->mda_id === $batch->mda_id;
    }
}
