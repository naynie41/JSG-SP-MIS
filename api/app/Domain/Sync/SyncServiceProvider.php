<?php

declare(strict_types=1);

namespace App\Domain\Sync;

use App\Domain\Access\Enums\PermissionAction;
use App\Domain\Access\Support\PermissionRegistry;
use App\Domain\Sync\Sources\MockSyncSource;
use App\Domain\Sync\Sources\SyncSourceResolver;
use Illuminate\Support\ServiceProvider;

/**
 * Data synchronization domain (FR-DSH-02, FR-REG-08). Registers the sync permissions
 * (System-Administrator territory per PRD §4 — they receive every permission). The
 * engine, source resolver and jobs are plain container-resolved services; the source
 * client is the {@see MockSyncSource} until a real endpoint
 * is provisioned (see {@see SyncSourceResolver}).
 */
class SyncServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $registry = $this->app->make(PermissionRegistry::class);
        $registry
            ->register('sync', PermissionAction::View, 'View sync connectors, runs and logs')
            ->register('sync', PermissionAction::Run, 'Run/trigger data synchronization');
    }
}
