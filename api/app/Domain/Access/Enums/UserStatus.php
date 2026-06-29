<?php

declare(strict_types=1);

namespace App\Domain\Access\Enums;

/**
 * Account status of a user (PRD FR-UAM-02: create, edit, suspend, deactivate).
 * Only `Active` users may authenticate (enforced in the auth build step).
 */
enum UserStatus: string
{
    case Active = 'active';
    case Suspended = 'suspended';
    case Deactivated = 'deactivated';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Suspended => 'Suspended',
            self::Deactivated => 'Deactivated',
        };
    }
}
