<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Authorization;

use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Services\ServiceRequestService;

/**
 * Authorizes delivery when the MDA holds an active read/serve grant opened by an
 * ACCEPTED Service Request (PRD §12, FR-OWN-06/07 · FR-BEN-06). Ownership is never
 * involved here — the grant only authorizes serving, and this asserts against that
 * explicit grant, not any generic cross-MDA access.
 */
class ServiceRequestAuthorizer implements DeliveryAuthorizer
{
    public function authorizes(string $mdaId, Beneficiary $beneficiary): bool
    {
        return ServiceRequestService::hasActiveGrant($beneficiary->id, $mdaId);
    }

    public function source(): string
    {
        return 'service_request';
    }
}
