<?php

declare(strict_types=1);

namespace App\Domain\Registry\Events;

use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\ServiceRequest;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when a non-owner MDA raises a Service Request (PRD §12, FR-OWN-06). The
 * owner MDA's approvers are notified so they can accept or decline.
 */
class ServiceRequestRaised
{
    use Dispatchable;

    public function __construct(
        public readonly ServiceRequest $request,
        public readonly ?User $actor = null,
    ) {}
}
