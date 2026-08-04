<?php

declare(strict_types=1);

namespace App\Domain\Reporting;

use App\Domain\Access\Enums\PermissionAction;
use App\Domain\Access\Support\PermissionRegistry;
use App\Domain\Notification\NotificationServiceProvider;
use App\Domain\Reporting\Events\ReportReady;
use App\Domain\Reporting\Gis\LoadGeoBoundaries;
use App\Domain\Reporting\Listeners\DeliverScheduledReport;
use App\Domain\Reporting\Services\AdminSettingsService;
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
    public function register(): void
    {
        // The console's Settings projection reports the SAME channel set the Notifier
        // sends through, so a stubbed provider can never be advertised as available.
        // Reporting depends on Notification, not the other way round.
        $this->app->singleton(
            AdminSettingsService::class,
            fn ($app): AdminSettingsService => new AdminSettingsService(
                $app->make(NotificationServiceProvider::CHANNELS),
            ),
        );
    }

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
