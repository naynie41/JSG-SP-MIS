<?php

declare(strict_types=1);

namespace App\Domain\Registry\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\ImportBatch;

/**
 * Authorization for bulk import batches (PRD FR-REG-02). Importing creates
 * beneficiaries, so upload/commit require `beneficiary.create`; viewing a batch
 * requires `beneficiary.view`. A batch is only visible/committable by its owning
 * MDA (oversight roles may read).
 */
class ImportBatchPolicy
{
    private function owns(User $user, ImportBatch $batch): bool
    {
        return $user->mda_id !== null && $user->mda_id === $batch->owner_mda_id;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('beneficiary.view');
    }

    public function view(User $user, ImportBatch $batch): bool
    {
        return $user->hasPermission('beneficiary.view')
            && ($this->owns($user, $batch) || $user->canAccessAllMdas());
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('beneficiary.create');
    }

    /** Confirm + commit a preview — owner MDA only. */
    public function commit(User $user, ImportBatch $batch): bool
    {
        return $user->hasPermission('beneficiary.create') && $this->owns($user, $batch);
    }
}
