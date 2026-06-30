<?php

declare(strict_types=1);

namespace App\Domain\Access\Support;

use App\Domain\Access\Enums\PermissionAction;

/**
 * In-memory registry of the permissions every module declares it needs
 * (PRD FR-UAM-05). Modules register their `module.action` permissions (typically
 * from their service provider's boot()); PermissionSynchronizer then persists
 * them to the database. This is the single source of truth for what permissions
 * exist in the system.
 */
final class PermissionRegistry
{
    /**
     * @var array<string, array{module: string, action: string, description: string}>
     */
    private array $items = [];

    public function register(string $module, PermissionAction $action, string $description = ''): static
    {
        $key = $module.'.'.$action->value;

        $this->items[$key] = [
            'module' => $module,
            'action' => $action->value,
            'description' => $description,
        ];

        return $this;
    }

    /**
     * All registered permission definitions, keyed and sorted by key.
     *
     * @return array<string, array{module: string, action: string, description: string}>
     */
    public function all(): array
    {
        ksort($this->items);

        return $this->items;
    }

    /**
     * @return list<string>
     */
    public function keys(): array
    {
        return array_keys($this->all());
    }
}
