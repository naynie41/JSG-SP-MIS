<?php

declare(strict_types=1);

namespace App\Domain\Access\Events;

use App\Domain\Access\Models\MdaAccessGrant;
use App\Domain\Access\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when a cross-MDA access grant is created (FR-DSH-01). Audit hook — the
 * audit-log step will listen to record who granted what to whom (FR-AUD-01).
 */
class CrossMdaAccessGranted
{
    use Dispatchable;

    public function __construct(
        public readonly MdaAccessGrant $grant,
        public readonly ?User $actor = null,
    ) {}
}
