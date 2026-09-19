<?php

declare(strict_types=1);

namespace App\Domain\Programme\Events;

use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Programme;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * A System Administrator has sent an MDA's programme back (revises PRD §10). The
 * reason travels on the programme's `decision_note` so the MDA can act on it.
 */
class ProgrammeRejected
{
    use Dispatchable;

    public function __construct(
        public readonly Programme $programme,
        public readonly ?User $actor = null,
    ) {}
}
