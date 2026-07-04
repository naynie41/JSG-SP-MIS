<?php

declare(strict_types=1);

namespace App\Domain\Programme\Enums;

/**
 * Whether a programme enrolls individuals or whole households (PRD FR-PRG-01,
 * §9). Drives how beneficiaries are enrolled in Phase 4's enrollment step.
 */
enum ProgrammeType: string
{
    case Household = 'household';
    case Individual = 'individual';
}
