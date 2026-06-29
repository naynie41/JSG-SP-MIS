<?php

declare(strict_types=1);

namespace App\Domain\Access\Enums;

/**
 * The seven predefined roles from PRD FR-UAM-01. These are seeded as data;
 * this enum gives code a type-safe handle on the well-known role keys.
 */
enum RoleKey: string
{
    case Executive = 'executive';
    case SpCoordination = 'sp_coordination';
    case MneOfficer = 'mne_officer';
    case MdaOfficer = 'mda_officer';
    case MdaAdmin = 'mda_admin';
    case DevelopmentPartner = 'development_partner';
    case SystemAdministrator = 'system_administrator';

    public function label(): string
    {
        return match ($this) {
            self::Executive => 'Executive',
            self::SpCoordination => 'SP Coordination',
            self::MneOfficer => 'M&E Officer',
            self::MdaOfficer => 'MDA Officer',
            self::MdaAdmin => 'MDA Admin',
            self::DevelopmentPartner => 'Development Partner',
            self::SystemAdministrator => 'System Administrator',
        };
    }
}
