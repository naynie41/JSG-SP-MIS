<?php

declare(strict_types=1);

namespace App\Domain\Graduation\Enums;

/** How a criteria set combines its individual rules (FR-GRD-01). */
enum CriteriaLogic: string
{
    case All = 'all'; // every rule must be met
    case Any = 'any'; // any one rule suffices
}
