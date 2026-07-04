<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Exceptions;

use DomainException;

/**
 * Thrown when recording a benefit for a beneficiary that has no open enrollment in
 * the programme (PRD FR-PRG-03/FR-BEN-01). The enrollment is what establishes that
 * the delivering MDA may serve this beneficiary.
 */
class NotEnrolledException extends DomainException {}
