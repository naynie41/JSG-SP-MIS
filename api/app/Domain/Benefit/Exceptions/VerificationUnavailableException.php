<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Exceptions;

use DomainException;

/**
 * Thrown when a verification method is recognised but cannot run because it needs
 * external access that is not configured (PRD FR-BEN-04). The message names the
 * access required — we never fabricate a successful verification.
 */
class VerificationUnavailableException extends DomainException {}
