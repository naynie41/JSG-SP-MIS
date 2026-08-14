<?php

declare(strict_types=1);

namespace App\Domain\Registry\Events;

use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\BeneficiaryServiceGrant;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when an owner MDA (or a System Administrator) withdraws a cross-MDA read
 * grant opened by an accepted Service Request (PRD FR-OWN-07).
 *
 * The serving MDA is notified that its access has ended. Anything consuming this event
 * must remember the recipient is, as of now, NO LONGER authorized to read the
 * beneficiary — so the notification identifies the service request, never the person.
 */
class BeneficiaryAccessRevoked
{
    use Dispatchable;

    public function __construct(
        public readonly BeneficiaryServiceGrant $grant,
        public readonly User $actor,
        public readonly ?string $reason = null,
    ) {}
}
