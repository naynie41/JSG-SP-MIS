<?php

declare(strict_types=1);

namespace App\Domain\Registry\Enums;

/**
 * Lifecycle of a request-to-serve (PRD FR-DUP-05). A requesting MDA asks the
 * owner MDA for permission to serve an existing beneficiary; ownership never
 * changes. On acceptance the requesting MDA gains the permitted serve access.
 */
enum ServeRequestStatus: string
{
    case Pending = 'pending';
    case Accepted = 'accepted';
    case Declined = 'declined';
}
