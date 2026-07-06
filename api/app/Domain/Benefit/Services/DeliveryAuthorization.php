<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Services;

use App\Domain\Benefit\Authorization\DeliveryAuthorizer;
use App\Domain\Registry\Models\Beneficiary;

/**
 * Decides whether a delivering MDA may record an intervention for a beneficiary
 * (PRD FR-BEN-06). The owner is always authorized; a NON-OWNER is authorized only
 * by an explicit, accepted authorization — checked through the registered
 * {@see DeliveryAuthorizer}s (Service Request now; Referral added in Phase 5
 * without touching the recorder). It never consults a generic serve seam.
 */
class DeliveryAuthorization
{
    /**
     * @param  iterable<DeliveryAuthorizer>  $authorizers
     */
    public function __construct(private readonly iterable $authorizers) {}

    /**
     * The basis on which the delivering MDA may serve this beneficiary, or null if
     * unauthorized. Returns 'owner' for the owning MDA, otherwise the authorizing
     * type's `source()` (e.g. 'service_request', 'referral').
     */
    public function basisFor(string $deliveringMdaId, Beneficiary $beneficiary): ?string
    {
        if ($deliveringMdaId === $beneficiary->owner_mda_id) {
            return 'owner';
        }

        foreach ($this->authorizers as $authorizer) {
            if ($authorizer->authorizes($deliveringMdaId, $beneficiary)) {
                return $authorizer->source();
            }
        }

        return null;
    }
}
