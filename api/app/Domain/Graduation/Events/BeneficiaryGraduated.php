<?php

declare(strict_types=1);

namespace App\Domain\Graduation\Events;

use App\Domain\Access\Models\User;
use App\Domain\Graduation\Models\GraduationEvent;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when a beneficiary/household graduates from a programme (FR-GRD-02). The
 * notification subscriber alerts the relevant MDAs (Phase 5 notifications).
 */
class BeneficiaryGraduated
{
    use Dispatchable;

    public function __construct(
        public readonly GraduationEvent $event,
        public readonly ?User $actor = null,
    ) {}
}
