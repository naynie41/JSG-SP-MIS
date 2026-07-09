<?php

declare(strict_types=1);

namespace App\Domain\Reporting;

use App\Domain\Access\Enums\PermissionAction;
use App\Domain\Access\Support\PermissionRegistry;
use App\Domain\Reporting\Events\ReportReady;
use App\Domain\Reporting\Gis\LoadGeoBoundaries;
use App\Domain\Reporting\Listeners\DeliverScheduledReport;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 * Wires the Reporting domain (PRD FR-RPT/FR-GIS): its permissions and the scheduled-
 * report delivery listener. Dashboards read de-identified aggregate snapshots scoped
 * to the caller (Executive state-wide, MDA own, Partner funded, SP Coordination
 * cross-MDA) — enforced in the reporting layer.
 */
class ReportingServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->make(PermissionRegistry::class)
            ->register('dashboard', PermissionAction::View, 'View dashboards')
            ->register('reporting', PermissionAction::View, 'View the report catalogue and runs')
            ->register('reporting', PermissionAction::Export, 'Generate and download reports');

        // A ready scheduled report is delivered to its validated recipients (FR-RPT-04).
        Event::listen(ReportReady::class, DeliverScheduledReport::class);

        // GIS boundary loader command (FR-GIS-01).
        if ($this->app->runningInConsole()) {
            $this->commands([LoadGeoBoundaries::class]);
        }
    }
}
