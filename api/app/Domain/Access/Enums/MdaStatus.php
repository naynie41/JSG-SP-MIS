<?php

declare(strict_types=1);

namespace App\Domain\Access\Enums;

/**
 * Lifecycle status of an MDA on the platform.
 */
enum MdaStatus: string
{
    case Active = 'active';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
        };
    }
}
