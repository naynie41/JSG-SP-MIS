<?php

declare(strict_types=1);

namespace App\Domain\Referral\Events;

use App\Domain\Access\Models\User;
use App\Domain\Referral\Models\Referral;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired on every referral status change, including creation (PRD FR-REF-05). BOTH
 * MDAs are notified. `action` is the transition name (created, accepted, …).
 */
class ReferralStatusChanged
{
    use Dispatchable;

    public function __construct(
        public readonly Referral $referral,
        public readonly string $action,
        public readonly ?User $actor = null,
    ) {}
}
