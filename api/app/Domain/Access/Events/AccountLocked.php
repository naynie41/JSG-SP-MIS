<?php

declare(strict_types=1);

namespace App\Domain\Access\Events;

use App\Domain\Access\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when an account is locked out after too many failed attempts. Audit
 * hook — the audit-log step (next) will listen for this (FR-UAM-06, FR-AUD-01).
 */
class AccountLocked
{
    use Dispatchable;

    public function __construct(
        public readonly User $user,
        public readonly ?string $ip = null,
    ) {}
}
