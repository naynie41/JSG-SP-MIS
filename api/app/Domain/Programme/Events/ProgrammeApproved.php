<?php

declare(strict_types=1);

namespace App\Domain\Programme\Events;

use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Programme;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * A System Administrator has cleared an MDA's programme for use (revises PRD §10).
 * From here it can carry activities, enrolments and benefits.
 */
class ProgrammeApproved
{
    use Dispatchable;

    public function __construct(
        public readonly Programme $programme,
        public readonly ?User $actor = null,
    ) {}
}
