<?php

declare(strict_types=1);

namespace App\Domain\Access\Events;

use App\Domain\Access\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when an MFA challenge (TOTP or recovery code) fails. Audit hook — the
 * audit-log step (next) will listen for this (FR-AUD-01).
 */
class MfaChallengeFailed
{
    use Dispatchable;

    public function __construct(
        public readonly User $user,
        public readonly ?string $ip = null,
    ) {}
}
