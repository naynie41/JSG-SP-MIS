<?php

declare(strict_types=1);

namespace App\Domain\Registry\Events;

use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\ServiceRequest;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when the owner MDA declines a Service Request (PRD §12, FR-OWN-06). The
 * requesting MDA is notified of the refusal and its reason.
 */
class ServiceRequestDeclined
{
    use Dispatchable;

    public function __construct(
        public readonly ServiceRequest $request,
        public readonly User $actor,
    ) {}
}
