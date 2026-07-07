<?php

declare(strict_types=1);

namespace App\Domain\Grievance\Events;

use App\Domain\Access\Models\User;
use App\Domain\Grievance\Models\Grievance;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when a grievance is resolved (PRD FR-GRM-02). Relevant parties — the staff
 * who logged it and the handling MDA's grievance team — are notified.
 */
class GrievanceResolved
{
    use Dispatchable;

    public function __construct(
        public readonly Grievance $grievance,
        public readonly User $actor,
    ) {}
}
