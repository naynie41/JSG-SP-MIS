<?php

declare(strict_types=1);

namespace App\Domain\Registry\Enums;

/**
 * Beneficiary lifecycle status. Maps to the shared status → badge variants
 * (DESIGN-SYSTEM.md §5.8): active→success, suspended→warning, flagged→danger.
 */
enum BeneficiaryStatus: string
{
    case Active = 'active';
    case Suspended = 'suspended';
    case Flagged = 'flagged';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
