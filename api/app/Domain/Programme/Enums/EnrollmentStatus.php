<?php

declare(strict_types=1);

namespace App\Domain\Programme\Enums;

/**
 * Lifecycle of a beneficiary/household enrollment in a programme (PRD FR-PRG-03).
 * `enrolled` is the single open state; the rest are terminal/paused states.
 */
enum EnrollmentStatus: string
{
    case Enrolled = 'enrolled';
    case Suspended = 'suspended';
    case Exited = 'exited';
    case Graduated = 'graduated';
}
