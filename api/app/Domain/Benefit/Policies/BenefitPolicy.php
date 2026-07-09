<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Policies;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;

/**
 * Authorization for the benefit ledger (PRD FR-BEN-01/02/04). Recording is done by
 * the delivering (programme owner) MDA; verification by that same MDA. Reads are
 * visible to the delivering MDA, the beneficiary's owner MDA (complete history),
 * and oversight (`cross-mda.view`).
 */
class BenefitPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('benefit.view');
    }

    public function view(User $user, Benefit $benefit): bool
    {
        if (! $user->hasPermission('benefit.view')) {
            return false;
        }
        if ($user->canAccessAllMdas() || $user->mda_id === $benefit->mda_id) {
            return true;
        }

        // The beneficiary's owner MDA sees benefits delivered to it by anyone.
        return $user->mda_id !== null && Beneficiary::query()
            ->withoutGlobalScope(MdaScope::class)
            ->whereKey($benefit->beneficiary_id)
            ->where('owner_mda_id', $user->mda_id)
            ->exists();
    }

    /**
     * Record a delivery under a catalog programme (PRD §10). Any MDA user may; the
     * delivering MDA is their own. Cross-MDA delivery to a non-owned beneficiary is
     * gated separately by the accepted Service Request / Referral authorization in
     * the BenefitRecorder — not by programme ownership.
     */
    public function record(User $user, Programme $programme): bool
    {
        return $user->hasPermission('benefit.create') && $user->mda_id !== null;
    }

    /** Verify a delivery — the delivering MDA only. */
    public function verify(User $user, Benefit $benefit): bool
    {
        return $user->hasPermission('benefit.approve') && $user->mda_id === $benefit->mda_id;
    }
}
