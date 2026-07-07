<?php

declare(strict_types=1);

namespace App\Domain\Referral;

use App\Domain\Access\Enums\PermissionAction;
use App\Domain\Access\Support\PermissionRegistry;
use App\Domain\Benefit\Services\DeliveryAuthorization;
use App\Domain\Referral\Authorization\ReferralAuthorizer;
use App\Domain\Referral\Models\Referral;
use App\Domain\Referral\Policies\ReferralPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Referral domain (PRD FR-REF): its permissions, the two-party
 * authorization policy, and — for FR-BEN-06 — the referral delivery authorizer, so
 * an accepted referral authorizes serving via the Phase 4 gate WITHOUT reusing the
 * Service Request. The referral lifecycle guards live in ReferralService.
 */
class ReferralServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // An accepted referral is a valid non-owner delivery authorization (FR-BEN-06),
        // registered alongside the Service Request authorizer — kept distinct.
        $this->app->tag([ReferralAuthorizer::class], DeliveryAuthorization::TAG);
    }

    public function boot(): void
    {
        $this->app->make(PermissionRegistry::class)
            ->register('referral', PermissionAction::View, 'View referrals')
            ->register('referral', PermissionAction::Create, 'Create referrals to another MDA')
            ->register('referral', PermissionAction::Edit, 'Act on the referral lifecycle')
            ->register('referral-sla', PermissionAction::Edit, 'Configure referral SLA windows');

        Gate::policy(Referral::class, ReferralPolicy::class);
    }
}
