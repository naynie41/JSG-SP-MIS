<?php

declare(strict_types=1);

namespace App\Domain\Graduation;

use App\Domain\Access\Enums\PermissionAction;
use App\Domain\Access\Support\PermissionRegistry;
use Illuminate\Support\ServiceProvider;

/**
 * Graduation management domain (FR-GRD-01, FR-GRD-02). Registers the graduation
 * permissions: `graduation.view` (see criteria, progress and history) and
 * `graduation.edit` (configure criteria and record a graduation). The progress and
 * graduation services are plain container-resolved services; the notification for a
 * recorded graduation is wired through the existing NotificationSubscriber.
 */
class GraduationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $registry = $this->app->make(PermissionRegistry::class);
        $registry
            ->register('graduation', PermissionAction::View, 'View graduation criteria, progress and records')
            ->register('graduation', PermissionAction::Edit, 'Configure graduation criteria and record graduations');
    }
}
