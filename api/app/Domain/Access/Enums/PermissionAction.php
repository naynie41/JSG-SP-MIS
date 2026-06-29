<?php

declare(strict_types=1);

namespace App\Domain\Access\Enums;

/**
 * The action half of a `module.action` permission (PRD FR-UAM-05).
 * Every module registers permissions using these actions so authorization
 * stays consistent across the system.
 */
enum PermissionAction: string
{
    case View = 'view';
    case Create = 'create';
    case Edit = 'edit';
    case Approve = 'approve';
    case Export = 'export';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}
