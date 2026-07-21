<?php

declare(strict_types=1);

namespace App\Domain\Registry\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Sharing\DataSharingGuard;

/**
 * Authorization for beneficiaries (PRD FR-OWN-02/03/05).
 *
 * The core rule: **only the owner MDA may edit the core profile**. Cross-MDA READ is
 * decided by the single {@see DataSharingGuard} (owner / oversight / consent-gated
 * Service-Request grant) — never re-implemented here. The reveal-only lookup seam is
 * gated separately by `beneficiary-lookup.view` (a distinct, minimal reveal).
 */
class BeneficiaryPolicy
{
    public function __construct(private readonly DataSharingGuard $sharing) {}

    private function owns(User $user, Beneficiary $beneficiary): bool
    {
        return $user->mda_id !== null && $user->mda_id === $beneficiary->owner_mda_id;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('beneficiary.view');
    }

    /**
     * Read a specific beneficiary — resolved through the one governed data-sharing
     * surface (owner, oversight, or a consent-satisfied Service-Request grant). READ
     * only; never confers edit.
     */
    public function view(User $user, Beneficiary $beneficiary): bool
    {
        return $this->sharing->canRead($user, $beneficiary);
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('beneficiary.create');
    }

    /**
     * Owner-only edit (FR-OWN-02). cross-mda.view does NOT grant edit.
     */
    public function update(User $user, Beneficiary $beneficiary): bool
    {
        return $user->hasPermission('beneficiary.edit') && $this->owns($user, $beneficiary);
    }

    public function delete(User $user, Beneficiary $beneficiary): bool
    {
        return $this->update($user, $beneficiary);
    }

    /**
     * Use the cross-MDA lookup/serve path (FR-OWN-03) — reveal fields only.
     */
    public function lookup(User $user): bool
    {
        return $user->hasPermission('beneficiary-lookup.view');
    }

    /**
     * Request ownership of a beneficiary owned by another MDA (FR-OWN-05).
     */
    public function requestTransfer(User $user, Beneficiary $beneficiary): bool
    {
        return $user->hasPermission('beneficiary.approve')
            && $user->mda_id !== null
            && ! $this->owns($user, $beneficiary);
    }

    /**
     * Approve/reject a transfer — only the current owner MDA (FR-OWN-05).
     */
    public function decideTransfer(User $user, Beneficiary $beneficiary): bool
    {
        return $user->hasPermission('beneficiary.approve') && $this->owns($user, $beneficiary);
    }
}
