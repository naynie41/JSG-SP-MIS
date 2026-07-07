<?php

declare(strict_types=1);

namespace App\Domain\Referral\Events;

use App\Domain\Referral\Models\Referral;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when a referral is overdue against its status SLA (PRD FR-REF-04/05). The
 * scheduled job escalates (bumping `escalation_level`) and dispatches this at each
 * new level; the notification subscriber alerts both MDAs + the escalation tier.
 * Nothing is auto-closed.
 */
class ReferralSlaBreached
{
    use Dispatchable;

    public function __construct(
        public readonly Referral $referral,
        public readonly int $level,
    ) {}
}
