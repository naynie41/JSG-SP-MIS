<?php

declare(strict_types=1);

namespace App\Domain\Registry\Events;

use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\OwnershipTransferRequest;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when an MDA requests ownership of another MDA's beneficiary (PRD FR-OWN-05).
 * The CURRENT owner MDA's approvers (`from_mda_id`) are notified to decide.
 */
class OwnershipTransferRequested
{
    use Dispatchable;

    public function __construct(
        public readonly OwnershipTransferRequest $transfer,
        public readonly User $actor,
    ) {}
}
