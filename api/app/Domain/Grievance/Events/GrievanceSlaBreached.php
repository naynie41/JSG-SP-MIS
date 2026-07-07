<?php

declare(strict_types=1);

namespace App\Domain\Grievance\Events;

use App\Domain\Grievance\Models\Grievance;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when a grievance is overdue against its category SLA (PRD FR-GRM-03). The
 * scheduled sweep escalates (bumping `escalation_level`) and dispatches this at each
 * new level; the notification subscriber alerts the handling MDA's grievance team +
 * the escalation tier. Nothing is auto-closed.
 */
class GrievanceSlaBreached
{
    use Dispatchable;

    public function __construct(
        public readonly Grievance $grievance,
        public readonly int $level,
    ) {}
}
