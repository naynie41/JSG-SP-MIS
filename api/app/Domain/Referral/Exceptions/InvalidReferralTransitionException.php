<?php

declare(strict_types=1);

namespace App\Domain\Referral\Exceptions;

use DomainException;

/**
 * Thrown when a referral transition is not valid from its current status
 * (PRD FR-REF-02 lifecycle guards) — e.g. accepting an already-rejected referral.
 * Mapped to a 422 by the controller.
 */
class InvalidReferralTransitionException extends DomainException {}
