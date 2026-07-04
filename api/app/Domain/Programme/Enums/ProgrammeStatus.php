<?php

declare(strict_types=1);

namespace App\Domain\Programme\Enums;

/**
 * Lifecycle of a programme (PRD FR-PRG-01). `archived` retires a programme
 * without deleting it (history + ledger stay intact).
 */
enum ProgrammeStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Closed = 'closed';
    case Archived = 'archived';
}
