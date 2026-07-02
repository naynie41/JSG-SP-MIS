<?php

declare(strict_types=1);

namespace App\Domain\Registry\Enums;

/**
 * Lifecycle of an ownership-transfer request (PRD FR-OWN-05).
 */
enum OwnershipTransferStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';
}
