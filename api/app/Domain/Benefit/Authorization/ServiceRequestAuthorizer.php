<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Authorization;

use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Sharing\DataSharingGuard;

/**
 * Authorizes delivery when the MDA holds an active read/serve grant opened by an
 * ACCEPTED Service Request (PRD §12, FR-OWN-06/07 · FR-BEN-06). Ownership is never
 * involved here — the grant only authorizes serving. The decision (grant + consent
 * gate) is resolved by the single {@see DataSharingGuard}, not re-implemented here.
 */
class ServiceRequestAuthorizer implements DeliveryAuthorizer
{
    public function __construct(private readonly DataSharingGuard $sharing) {}

    public function authorizes(string $mdaId, Beneficiary $beneficiary): bool
    {
        return $this->sharing->mdaMayServeViaGrant($mdaId, $beneficiary);
    }

    public function source(): string
    {
        return 'service_request';
    }
}
