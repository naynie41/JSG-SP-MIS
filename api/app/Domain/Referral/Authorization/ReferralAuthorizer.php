<?php

declare(strict_types=1);

namespace App\Domain\Referral\Authorization;

use App\Domain\Benefit\Authorization\DeliveryAuthorizer;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Models\Beneficiary;

/**
 * Authorizes delivery when the MDA holds an accepted Referral for the beneficiary
 * (PRD FR-REF-02, FR-BEN-06). This is DISTINCT from the request-to-serve Service
 * Request — it consults the `referrals` table only, never a BeneficiaryServiceGrant,
 * so the two authorization paths stay separate. Ownership is never involved (the
 * owner MDA consented by referring).
 */
class ReferralAuthorizer implements DeliveryAuthorizer
{
    public function authorizes(string $mdaId, Beneficiary $beneficiary): bool
    {
        return Referral::authorizesDelivery($beneficiary->id, $mdaId);
    }

    public function source(): string
    {
        return 'referral';
    }
}
