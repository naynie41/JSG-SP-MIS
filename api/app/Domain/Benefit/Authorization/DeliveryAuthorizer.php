<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Authorization;

use App\Domain\Registry\Models\Beneficiary;

/**
 * A single kind of authorization that lets a NON-OWNER MDA record an intervention
 * for a beneficiary (PRD FR-BEN-06). Each concrete authorizer represents one
 * explicit, accepted authorization type — an accepted Service Request
 * (request-to-serve, R2.3) now; an accepted Referral to the MDA (Phase 5) when it
 * ships. This is deliberately NOT a generic serve seam: only these explicit,
 * per-beneficiary authorizations count.
 */
interface DeliveryAuthorizer
{
    /** Whether this authorization currently entitles the MDA to deliver to the beneficiary. */
    public function authorizes(string $mdaId, Beneficiary $beneficiary): bool;

    /** Short identifier of the authorization type, recorded in the audit trail. */
    public function source(): string;
}
