<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Exceptions;

use RuntimeException;

/**
 * Thrown when the processing-consent gate is ON (DPO policy) and the beneficiary
 * has not granted processing consent, so a new intervention cannot be recorded
 * (NFR-PRV-01). Distinct from an authorization failure — the MDA may be authorized
 * to serve, but the subject's consent gate is not satisfied.
 */
class ProcessingConsentRequiredException extends RuntimeException {}
