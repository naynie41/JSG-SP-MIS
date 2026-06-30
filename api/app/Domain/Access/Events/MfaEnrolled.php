<?php

declare(strict_types=1);

namespace App\Domain\Access\Events;

use App\Domain\Access\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when a user enables MFA. Audit hook — the audit-log step (next) will
 * listen for this to record the enrolment (FR-AUD-01).
 */
class MfaEnrolled
{
    use Dispatchable;

    public function __construct(public readonly User $user) {}
}
