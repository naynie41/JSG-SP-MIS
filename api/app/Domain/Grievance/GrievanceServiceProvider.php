<?php

declare(strict_types=1);

namespace App\Domain\Grievance;

use App\Domain\Access\Enums\PermissionAction;
use App\Domain\Access\Support\PermissionRegistry;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Grievance\Policies\GrievancePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Grievance domain (PRD FR-GRM): its permissions and the MDA-scoped
 * authorization policy. The lifecycle guards live in GrievanceService.
 */
class GrievanceServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->make(PermissionRegistry::class)
            ->register('grievance', PermissionAction::View, 'View grievances')
            ->register('grievance', PermissionAction::Create, 'Log grievances')
            ->register('grievance', PermissionAction::Edit, 'Assign and resolve grievances')
            ->register('grievance-sla', PermissionAction::Edit, 'Configure grievance SLA windows');

        Gate::policy(Grievance::class, GrievancePolicy::class);
    }
}
