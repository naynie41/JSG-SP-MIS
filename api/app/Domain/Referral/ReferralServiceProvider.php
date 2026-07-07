<?php

declare(strict_types=1);

namespace App\Domain\Referral;

use App\Domain\Access\Enums\PermissionAction;
use App\Domain\Access\Support\PermissionRegistry;
use App\Domain\Referral\Models\Referral;
use App\Domain\Referral\Policies\ReferralPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Referral domain (PRD FR-REF): its permissions and the two-party
 * authorization policy. The referral lifecycle guards live in ReferralService.
 */
class ReferralServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->make(PermissionRegistry::class)
            ->register('referral', PermissionAction::View, 'View referrals')
            ->register('referral', PermissionAction::Create, 'Create referrals to another MDA')
            ->register('referral', PermissionAction::Edit, 'Act on the referral lifecycle');

        Gate::policy(Referral::class, ReferralPolicy::class);
    }
}
