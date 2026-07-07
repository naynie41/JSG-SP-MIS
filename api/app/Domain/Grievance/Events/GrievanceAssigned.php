<?php

declare(strict_types=1);

namespace App\Domain\Grievance\Events;

use App\Domain\Access\Models\User;
use App\Domain\Grievance\Models\Grievance;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when a grievance is assigned (PRD FR-GRM-02). The assignee is notified.
 */
class GrievanceAssigned
{
    use Dispatchable;

    public function __construct(
        public readonly Grievance $grievance,
        public readonly User $assignee,
        public readonly ?User $actor = null,
    ) {}
}
