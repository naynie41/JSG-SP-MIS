<?php

declare(strict_types=1);

namespace App\Domain\Programme\Events;

use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Programme;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * An MDA has put a programme of its own in front of the System Administrator
 * (revises PRD §10). Fired on creation and on every re-submission after a
 * rejection, so the queue is never silently long.
 */
class ProgrammeSubmitted
{
    use Dispatchable;

    public function __construct(
        public readonly Programme $programme,
        public readonly ?User $actor = null,
    ) {}
}
