<?php

declare(strict_types=1);

namespace App\Domain\Sharing;

use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Services\ServiceRequestService;

/**
 * The single authorization surface for cross-MDA beneficiary data sharing (FR-DSH-01,
 * NFR-PRV-01). Every cross-MDA READ of a full record and every cross-MDA SERVE
 * (intervention on a non-owned beneficiary) resolves through here to exactly one
 * {@see SharingBasis}: owner, oversight, or an accepted-Service-Request grant — and
 * the grant path is additionally gated on the beneficiary's sharing CONSENT.
 *
 * There is no other sharing path: ownership (owner-only edit) and the reveal-only
 * lookup seam (name/id only, no full PII) sit outside this and are unchanged; anything
 * else is denied. The scattered `hasActiveGrant` checks (BeneficiaryPolicy,
 * BeneficiaryController::show, ServiceRequestAuthorizer) all delegate here.
 *
 * Four bases, two of them grants with deliberately different WIDTH: a Service-Request
 * grant opens ONE beneficiary the owner approved; an administrative grant (FR-UAM-03)
 * opens an MDA. The administrative one is also what `MdaScope` widens list queries
 * with, so it must be recognised here too — otherwise the layers disagree and a record
 * the list showed 404s when opened.
 */
class DataSharingGuard
{
    /** May this user READ the full beneficiary record (cross-MDA aware)? */
    public function canRead(User $user, Beneficiary $beneficiary): bool
    {
        return $this->basisFor($user, $beneficiary) !== SharingBasis::None;
    }

    /**
     * The basis on which this user may read the beneficiary, or {@see SharingBasis::None}.
     * Owner and oversight reads are role/ownership-based; the grant read is
     * consent-gated.
     */
    public function basisFor(User $user, Beneficiary $beneficiary): SharingBasis
    {
        if (! $user->hasPermission('beneficiary.view')) {
            return SharingBasis::None;
        }
        if ($user->mda_id !== null && $user->mda_id === $beneficiary->owner_mda_id) {
            return SharingBasis::Owner;
        }
        if ($user->canAccessAllMdas()) {
            return SharingBasis::Oversight;
        }
        if ($user->mda_id !== null && $this->grantedAndConsented($beneficiary, $user->mda_id)) {
            return SharingBasis::ServiceGrant;
        }
        if ($this->hasAdminGrant($user, $beneficiary) && $this->adminGrantConsentSatisfied($beneficiary)) {
            return SharingBasis::AdminGrant;
        }

        return SharingBasis::None;
    }

    /**
     * An administrative whole-MDA grant covering the beneficiary's OWNER MDA
     * (FR-UAM-03) — the same set `MdaScope` widens list queries with, so the two
     * enforcement layers agree. Without this the guard denied a record the list had
     * already shown: a 404 on a row the user could see, and summary PII released under
     * no basis the guard could name.
     */
    private function hasAdminGrant(User $user, Beneficiary $beneficiary): bool
    {
        // `accessibleMdaIds()` is the user's own MDA PLUS their granted MDAs, so the
        // second clause is what makes this specifically a GRANT — the owner case is
        // already handled above and must not be reported as an administrative grant.
        return in_array($beneficiary->owner_mda_id, $user->accessibleMdaIds(), true)
            && $user->mda_id !== $beneficiary->owner_mda_id;
    }

    /** An administrative grant carries its own consent switch (DPO decision — config). */
    public function adminGrantConsentSatisfied(Beneficiary $beneficiary): bool
    {
        return ! $this->adminGrantConsentRequired() || $beneficiary->hasSharingConsent();
    }

    public function adminGrantConsentRequired(): bool
    {
        return (bool) config('sharing.admin_grant_requires_consent', true);
    }

    /**
     * May the delivering MDA record an intervention against this beneficiary via an
     * accepted Service Request grant (FR-BEN-06)? Consent-gated. (Ownership is handled
     * by the delivery authorizer separately — the owner never needs a grant.)
     */
    public function mdaMayServeViaGrant(string $mdaId, Beneficiary $beneficiary): bool
    {
        return $this->grantedAndConsented($beneficiary, $mdaId);
    }

    /** Whether a grant is both present AND currently effective under the consent rule. */
    private function grantedAndConsented(Beneficiary $beneficiary, string $mdaId): bool
    {
        return ServiceRequestService::hasActiveGrant($beneficiary->id, $mdaId)
            && $this->consentSatisfied($beneficiary);
    }

    /** A cross-MDA grant is effective only while consent is satisfied (where required). */
    public function consentSatisfied(Beneficiary $beneficiary): bool
    {
        return ! $this->consentRequired() || $beneficiary->hasSharingConsent();
    }

    /** Whether the cross-MDA consent gate is switched on (NDPA/DPO policy — config). */
    public function consentRequired(): bool
    {
        return (bool) config('sharing.cross_mda_requires_consent', true);
    }
}
