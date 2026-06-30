<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Access\Services\PermissionSynchronizer;
use Illuminate\Console\Command;

/**
 * Syncs the registered module permissions into the database. Run after adding
 * permissions to a module's registration.
 */
class SyncPermissions extends Command
{
    protected $signature = 'permissions:sync';

    protected $description = 'Synchronize registered module permissions into the database';

    public function handle(PermissionSynchronizer $synchronizer): int
    {
        $count = $synchronizer->sync();

        $this->info("Synced {$count} permission(s).");

        return self::SUCCESS;
    }
}
