<?php

declare(strict_types=1);

namespace App\Domain\Registry;

use App\Domain\Access\Enums\PermissionAction;
use App\Domain\Access\Support\PermissionRegistry;
use App\Domain\Programme\Services\ProgrammeMatchingRouter;
use App\Domain\Registry\Contracts\BeneficiaryRouter;
use App\Domain\Registry\Contracts\DuplicateChecker;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Imports\Adapters\DefaultImportAdapter;
use App\Domain\Registry\Imports\Adapters\KoboAdapter;
use App\Domain\Registry\Imports\Adapters\OdkAdapter;
use App\Domain\Registry\Imports\Adapters\SourceAdapterRegistry;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryDocument;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ServiceRequest;
use App\Domain\Registry\Policies\BeneficiaryDocumentPolicy;
use App\Domain\Registry\Policies\BeneficiaryPolicy;
use App\Domain\Registry\Policies\HouseholdPolicy;
use App\Domain\Registry\Policies\ImportBatchPolicy;
use App\Domain\Registry\Policies\OwnerMdaPolicy;
use App\Domain\Registry\Services\NullDuplicateChecker;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Registry domain: its permissions, the beneficiary authorization
 * policy (owner-only edit, FR-OWN-02), and the auto-route extension point
 * (FR-OWN-04, no-op until Phase 4).
 */
class RegistryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Programme matching (FR-OWN-04): suggest-then-confirm auto-routing.
        $this->app->bind(BeneficiaryRouter::class, ProgrammeMatchingRouter::class);

        // Fuzzy duplicate matching binds a real checker here in Phase 3.
        $this->app->bind(DuplicateChecker::class, NullDuplicateChecker::class);

        // Hybrid-registry source adapters (PRD FR-REG-02). Add a new inbound
        // source (e.g. a government system) by registering its adapter here.
        $this->app->singleton(SourceAdapterRegistry::class, function (): SourceAdapterRegistry {
            $registry = new SourceAdapterRegistry;
            $registry->register(new DefaultImportAdapter(RegistrationSource::Excel));
            $registry->register(new DefaultImportAdapter(RegistrationSource::Csv));
            $registry->register(new KoboAdapter);
            $registry->register(new OdkAdapter);

            return $registry;
        });
    }

    public function boot(): void
    {
        $this->registerPermissions($this->app->make(PermissionRegistry::class));
        Gate::policy(Beneficiary::class, BeneficiaryPolicy::class);
        Gate::policy(Household::class, HouseholdPolicy::class);
        Gate::policy(ImportBatch::class, ImportBatchPolicy::class);
        Gate::policy(BeneficiaryDocument::class, BeneficiaryDocumentPolicy::class);
        Gate::policy(ServiceRequest::class, OwnerMdaPolicy::class);
    }

    private function registerPermissions(PermissionRegistry $registry): void
    {
        $registry
            ->register('beneficiary', PermissionAction::View, 'View beneficiaries')
            ->register('beneficiary', PermissionAction::Create, 'Register beneficiaries')
            ->register('beneficiary', PermissionAction::Edit, 'Edit beneficiary core profile (owner MDA only)')
            ->register('beneficiary', PermissionAction::Export, 'Export beneficiaries')
            ->register('beneficiary', PermissionAction::Approve, 'Request/approve ownership transfer')
            ->register('beneficiary-lookup', PermissionAction::View, 'Look up beneficiaries across MDAs (reveal fields only)')
            // Unmask NIN/BVN in exports (SECURITY.md — Export of beneficiary data).
            // System Administrator only by default (via implicit all-permissions);
            // NEVER bundled into any role. Without it, every export masks identifiers
            // exactly as the on-screen list does.
            ->register('export', PermissionAction::RevealPii, 'Reveal beneficiary NIN/BVN in exports (unmasked)')
            ->register('household', PermissionAction::View, 'View households')
            ->register('household', PermissionAction::Create, 'Create households')
            ->register('household', PermissionAction::Edit, 'Edit households');
    }
}
