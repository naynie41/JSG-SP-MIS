<?php

declare(strict_types=1);

namespace App\Domain\Registry\Enums;

/**
 * A member's role within a household (relative to the head).
 */
enum HouseholdRole: string
{
    case Head = 'head';
    case Spouse = 'spouse';
    case Child = 'child';
    case Dependent = 'dependent';
    case Other = 'other';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
