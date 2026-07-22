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
    case RevealPii = 'reveal_pii';
    case Run = 'run';
    case AccessRequest = 'access_request';

    public function label(): string
    {
        return match ($this) {
            self::RevealPii => 'Reveal PII',
            self::AccessRequest => 'Access request',
            default => ucfirst($this->value),
        };
    }
}
