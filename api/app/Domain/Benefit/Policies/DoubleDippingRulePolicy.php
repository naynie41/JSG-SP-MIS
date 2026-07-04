<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Policies;

use App\Domain\Access\Models\User;

/**
 * Authorization for double-dipping rule configuration (PRD FR-BEN-05) — a
 * system-wide admin/coordination concern.
 */
class DoubleDippingRulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('double-dipping.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('double-dipping.edit');
    }

    public function update(User $user): bool
    {
        return $user->hasPermission('double-dipping.edit');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermission('double-dipping.edit');
    }
}
