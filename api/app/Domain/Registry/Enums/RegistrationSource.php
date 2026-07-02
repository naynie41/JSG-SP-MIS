<?php

declare(strict_types=1);

namespace App\Domain\Registry\Enums;

/**
 * Where a beneficiary/household record came from (PRD §6.1 provenance). Phase 2
 * ingests `manual` and `excel`/`csv`; the other sources have endpoints later but
 * their provenance values are defined now.
 */
enum RegistrationSource: string
{
    case Manual = 'manual';
    case Excel = 'excel';
    case Csv = 'csv';
    case Kobo = 'kobo';
    case Odk = 'odk';
    case Api = 'api';
    case Socu = 'socu';
    case GovernmentSystem = 'government_system';
}
