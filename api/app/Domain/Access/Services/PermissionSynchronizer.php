<?php

declare(strict_types=1);

namespace App\Domain\Access\Services;

use App\Domain\Access\Models\Permission;
use App\Domain\Access\Support\PermissionRegistry;

/**
 * Persists the registered permissions (PermissionRegistry) to the database.
 * Idempotent: upserts by key. Does not delete unknown rows, so a permission a
 * module stops registering must be removed deliberately.
 */
class PermissionSynchronizer
{
    public function __construct(private readonly PermissionRegistry $registry) {}

    /**
     * @return int the number of permissions synced
     */
    public function sync(): int
    {
        $definitions = $this->registry->all();

        foreach ($definitions as $key => $definition) {
            Permission::updateOrCreate(
                ['key' => $key],
                [
                    'module' => $definition['module'],
                    'action' => $definition['action'],
                    'description' => $definition['description'],
                ],
            );
        }

        return count($definitions);
    }
}
