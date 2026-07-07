<?php

declare(strict_types=1);

namespace App\Domain\Grievance\Enums;

/**
 * How the grievance reached the staff member who logged it (PRD FR-GRM-01). Staff
 * capture only — there is no citizen self-service channel.
 */
enum GrievanceChannel: string
{
    case WalkIn = 'walk_in';
    case Phone = 'phone';
    case Email = 'email';
    case Sms = 'sms';
    case Online = 'online';
    case Other = 'other';
}
