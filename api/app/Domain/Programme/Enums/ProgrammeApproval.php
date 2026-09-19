<?php

declare(strict_types=1);

namespace App\Domain\Programme\Enums;

/**
 * Whether a programme has been cleared for use (revises PRD §10).
 *
 * Separate from {@see ProgrammeStatus}, which is the delivery lifecycle. A central
 * catalog entry is `approved` the moment it is created — the System Administrator
 * creating it IS the approval. A programme an MDA creates starts `pending` and
 * carries no activities, enrolments or benefits until a System Administrator
 * decides.
 */
enum ProgrammeApproval: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';

    /** Plain English, as the console and reports say it. */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Waiting for approval',
            self::Approved => 'Approved',
            self::Rejected => 'Sent back',
        };
    }
}
