<?php

declare(strict_types=1);

namespace App\Domain\Benefit;

use App\Domain\Access\Enums\PermissionAction;
use App\Domain\Access\Support\PermissionRegistry;
use App\Domain\Benefit\Authorization\ServiceRequestAuthorizer;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Benefit\Models\BenefitFlag;
use App\Domain\Benefit\Models\BenefitImportBatch;
use App\Domain\Benefit\Models\DoubleDippingRule;
use App\Domain\Benefit\Policies\BenefitFlagPolicy;
use App\Domain\Benefit\Policies\BenefitImportPolicy;
use App\Domain\Benefit\Policies\BenefitPolicy;
use App\Domain\Benefit\Policies\DoubleDippingRulePolicy;
use App\Domain\Benefit\Services\DeliveryAuthorization;
use App\Domain\Benefit\Services\VerifierRegistry;
use App\Domain\Benefit\Services\Verifiers\BiometricVerifier;
use App\Domain\Benefit\Services\Verifiers\FieldConfirmationVerifier;
use App\Domain\Benefit\Services\Verifiers\OtpVerifier;
use App\Domain\Benefit\Services\Verifiers\SignatureVerifier;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Benefit domain (PRD FR-BEN): its permissions, the ledger authorization
 * policy, and the verification strategies. Enabling a stubbed verifier (OTP,
 * biometric) later is a one-line binding change here.
 */
class BenefitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(VerifierRegistry::class, fn (): VerifierRegistry => new VerifierRegistry([
            new FieldConfirmationVerifier,
            new SignatureVerifier,
            new OtpVerifier,        // stub — unavailable until an SMS/OTP gateway is provided
            new BiometricVerifier,  // stub — unavailable until biometric/NIMC access is provided
        ]));

        // Non-owner delivery authorization (FR-BEN-06). Each domain registers its
        // own authorizer under the shared tag — a Service Request here, a Referral
        // from the Referral domain — so the recorder never changes.
        $this->app->tag([ServiceRequestAuthorizer::class], DeliveryAuthorization::TAG);
        $this->app->singleton(DeliveryAuthorization::class, fn ($app): DeliveryAuthorization => new DeliveryAuthorization(
            $app->tagged(DeliveryAuthorization::TAG),
        ));
    }

    public function boot(): void
    {
        $this->app->make(PermissionRegistry::class)
            ->register('benefit', PermissionAction::View, 'View the benefit ledger')
            ->register('benefit', PermissionAction::Create, 'Record benefit deliveries')
            ->register('benefit', PermissionAction::Approve, 'Verify benefit deliveries')
            ->register('double-dipping', PermissionAction::View, 'View double-dipping rules')
            ->register('double-dipping', PermissionAction::Edit, 'Configure double-dipping rules');

        Gate::policy(Benefit::class, BenefitPolicy::class);
        Gate::policy(BenefitImportBatch::class, BenefitImportPolicy::class);
        Gate::policy(DoubleDippingRule::class, DoubleDippingRulePolicy::class);
        Gate::policy(BenefitFlag::class, BenefitFlagPolicy::class);
    }
}
