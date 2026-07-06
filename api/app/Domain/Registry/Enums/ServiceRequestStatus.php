<?php

declare(strict_types=1);

namespace App\Domain\Registry\Enums;

/**
 * Lifecycle of a Service Request (PRD §12, FR-OWN-06): a requesting MDA asks the
 * owner MDA for permission to serve an existing beneficiary; ownership never
 * changes. The only transitions are pending → accepted and pending → declined.
 * On acceptance a read-access grant is opened (FR-OWN-07).
 */
enum ServiceRequestStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Declined = 'declined';
}
