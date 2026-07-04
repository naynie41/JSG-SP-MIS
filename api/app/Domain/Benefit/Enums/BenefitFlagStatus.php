<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Enums;

/**
 * Review state of a potential double-dipping flag (PRD FR-BEN-05). A flag never
 * blocks delivery — it is surfaced for a human to `confirmed` (real overlap) or
 * `dismissed` (acceptable).
 */
enum BenefitFlagStatus: string
{
    case Open = 'open';
    case Confirmed = 'confirmed';
    case Dismissed = 'dismissed';
}
