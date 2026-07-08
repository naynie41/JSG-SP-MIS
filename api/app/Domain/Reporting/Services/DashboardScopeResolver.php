<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\ProgrammeFunder;
use App\Domain\Reporting\Support\DashboardScope;

/**
 * Resolves the {@see DashboardScope} for a user (PRD FR-DSH-01):
 *
 *  - oversight (`cross-mda.view`) → state-wide;
 *  - Development Partner → their funded programmes only;
 *  - any other MDA user → their accessible MDAs (own + active cross-MDA grants).
 *
 * The partner branch is checked before the MDA fallback; partners never hold
 * `cross-mda.view`, so oversight and partner are mutually exclusive.
 */
class DashboardScopeResolver
{
    public function forUser(User $user): DashboardScope
    {
        if ($user->canAccessAllMdas()) {
            return DashboardScope::stateWide();
        }

        if ($user->role?->key === RoleKey::DevelopmentPartner->value) {
            return DashboardScope::partner(
                ProgrammeFunder::programmeIdsForUser($user->id),
                'Funded programmes',
            );
        }

        return DashboardScope::mda($user->accessibleMdaIds());
    }
}
