<?php

declare(strict_types=1);

namespace App\Domain\Programme\Enums;

/**
 * Lifecycle of an activity under a programme (PRD FR-PRG-02). `archived` retires
 * it without deleting it.
 */
enum ActivityStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Completed = 'completed';
    case Archived = 'archived';
}
