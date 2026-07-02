<?php

declare(strict_types=1);

namespace App\Domain\Registry\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\BeneficiaryDocument;

/**
 * Authorization for a supporting document instance (PRD FR-REG-07). Mirrors the
 * beneficiary rules: reading needs `beneficiary.view` and ownership (or oversight
 * via cross-mda.view); deleting is owner-only and needs `beneficiary.edit`.
 * Listing/uploading are authorized against the parent beneficiary in the
 * controller (BeneficiaryPolicy view/update).
 */
class BeneficiaryDocumentPolicy
{
    private function owns(User $user, BeneficiaryDocument $document): bool
    {
        return $user->mda_id !== null && $user->mda_id === $document->owner_mda_id;
    }

    /** Download/view a specific document. */
    public function view(User $user, BeneficiaryDocument $document): bool
    {
        return $user->hasPermission('beneficiary.view')
            && ($this->owns($user, $document) || $user->canAccessAllMdas());
    }

    /** Delete a document — owner MDA only. */
    public function delete(User $user, BeneficiaryDocument $document): bool
    {
        return $user->hasPermission('beneficiary.edit') && $this->owns($user, $document);
    }
}
