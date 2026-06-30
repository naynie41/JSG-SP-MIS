<?php

declare(strict_types=1);

namespace App\Domain\Access\Events;

use App\Domain\Access\Models\MdaAccessGrant;
use App\Domain\Access\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when a cross-MDA access grant is revoked (FR-DSH-01). Audit hook for the
 * audit-log step (FR-AUD-01).
 */
class CrossMdaAccessRevoked
{
    use Dispatchable;

    public function __construct(
        public readonly MdaAccessGrant $grant,
        public readonly ?User $actor = null,
    ) {}
}
