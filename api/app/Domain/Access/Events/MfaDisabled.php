<?php

declare(strict_types=1);

namespace App\Domain\Access\Events;

use App\Domain\Access\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when a user disables MFA. Audit hook (FR-AUD-01).
 */
class MfaDisabled
{
    use Dispatchable;

    public function __construct(public readonly User $user) {}
}
