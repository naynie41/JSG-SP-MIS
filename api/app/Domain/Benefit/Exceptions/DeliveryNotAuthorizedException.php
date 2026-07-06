<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Exceptions;

use DomainException;

/**
 * Thrown when a NON-OWNER MDA tries to record an intervention for a beneficiary it
 * does not own without an explicit accepted authorization — an accepted Service
 * Request (R2.3) or an accepted Referral (Phase 5, FR-BEN-06). Ownership is never
 * changed; the delivery is simply refused.
 */
class DeliveryNotAuthorizedException extends DomainException {}
