<?php

declare(strict_types=1);

namespace App\Domain\Referral\Scopes;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

/**
 * Two-party MDA scoping for referrals (PRD FR-REF, FR-UAM-03). A referral is
 * visible when the user's accessible MDAs include EITHER side (`from_mda_id` or
 * `to_mda_id`) — so both the originating and receiving MDA see it. Mirrors
 * {@see MdaScope}; oversight (`cross-mda.view`) sees all.
 */
class ReferralScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (! Auth::hasUser()) {
            return;
        }

        $user = Auth::user();
        if (! $user instanceof User || $user->canAccessAllMdas()) {
            return;
        }

        $accessibleMdaIds = $user->accessibleMdaIds();
        if ($accessibleMdaIds === []) {
            $builder->whereRaw('1 = 0');

            return;
        }

        $builder->where(function (Builder $query) use ($model, $accessibleMdaIds): void {
            $query->whereIn($model->qualifyColumn('from_mda_id'), $accessibleMdaIds)
                ->orWhereIn($model->qualifyColumn('to_mda_id'), $accessibleMdaIds);
        });
    }
}
