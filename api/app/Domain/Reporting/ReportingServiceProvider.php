<?php

declare(strict_types=1);

namespace App\Domain\Reporting;

use App\Domain\Access\Enums\PermissionAction;
use App\Domain\Access\Support\PermissionRegistry;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Reporting domain (PRD FR-RPT/FR-GIS): its permissions. Dashboards read
 * de-identified aggregate snapshots scoped to the caller (Executive state-wide, MDA
 * own, Partner funded, SP Coordination cross-MDA) — enforced in the reporting layer.
 */
class ReportingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->make(PermissionRegistry::class)
            ->register('dashboard', PermissionAction::View, 'View dashboards')
            ->register('reporting', PermissionAction::View, 'View the report catalogue and runs')
            ->register('reporting', PermissionAction::Export, 'Generate and download reports');
    }
}
