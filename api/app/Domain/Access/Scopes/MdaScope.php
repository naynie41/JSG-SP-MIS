<?php

declare(strict_types=1);

namespace App\Domain\Access\Scopes;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

/**
 * Central MDA data-scoping (PRD FR-UAM-03, FR-DSH-01). Restricts every query on
 * an MDA-owned model to the authenticated user's accessible MDAs:
 *
 *  - No authenticated user (console, queue, pre-auth) → no restriction.
 *  - User holds the `cross-mda.view` permission → no restriction (oversight roles).
 *  - Otherwise → limited to the user's own MDA plus any active cross-MDA grants.
 *
 * Applied via the ScopedToMda trait so modules opt in by default and scoping is
 * never re-implemented ad hoc in controllers (SECURITY.md §3).
 */
class MdaScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Only act on an already-resolved user. Calling Auth::user() here would
        // re-enter the auth guard while it is resolving the user from a token
        // (which itself loads the User model) and recurse infinitely.
        if (! Auth::hasUser()) {
            return;
        }

        $user = Auth::user();

        // System / unauthenticated context: do not constrain.
        if (! $user instanceof User) {
            return;
        }

        // Oversight roles see across all MDAs.
        if ($user->canAccessAllMdas()) {
            return;
        }

        // Only models that opt into MDA scoping expose an ownership column.
        if (! $model instanceof MdaScoped) {
            return;
        }

        $column = $model->qualifyColumn($model->mdaOwnershipColumn());
        $accessibleMdaIds = $user->accessibleMdaIds();

        if ($accessibleMdaIds === []) {
            // No MDA and no grants → see nothing (deny by default).
            $builder->whereRaw('1 = 0');

            return;
        }

        $builder->whereIn($column, $accessibleMdaIds);
    }
}
