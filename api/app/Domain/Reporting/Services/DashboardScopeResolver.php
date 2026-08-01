<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\Activity;
use App\Domain\Reporting\Support\DashboardScope;

/**
 * Resolves the {@see DashboardScope} for a user (PRD FR-DSH-01):
 *
 *  - oversight (`cross-mda.view`) → state-wide;
 *  - Development Partner → their FUNDED programmes only (Phase 6P);
 *  - any other MDA user → their accessible MDAs (own + active cross-MDA grants).
 *
 * The partner branch is checked before the MDA fallback; partners never hold
 * `cross-mda.view`, so oversight and partner are mutually exclusive.
 *
 * A partner's funded scope is derived from `activities.funding_partner_id` (the
 * queryable attribution, Phase 6P) — the distinct programmes of the activities they
 * fund — so scope, budget and delivery always agree, and a partner sees ONLY their
 * funded data (SECURITY.md — Development Partner: funded programmes only).
 */
class DashboardScopeResolver
{
    public function forUser(User $user): DashboardScope
    {
        if ($user->canAccessAllMdas()) {
            return DashboardScope::stateWide();
        }

        if ($user->role?->key === RoleKey::DevelopmentPartner->value) {
            $fundedProgrammeIds = Activity::query()->withoutGlobalScope(MdaScope::class)
                ->where('funding_partner_id', $user->id)
                ->distinct()->pluck('programme_id')->all();

            return DashboardScope::partner($fundedProgrammeIds, $user->id, 'Funded programmes');
        }

        return DashboardScope::mda($user->accessibleMdaIds());
    }
}
