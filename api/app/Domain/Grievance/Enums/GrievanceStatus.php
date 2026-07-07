<?php

declare(strict_types=1);

namespace App\Domain\Grievance\Enums;

/**
 * Grievance lifecycle (PRD FR-GRM-02, §8.4):
 *
 *   Open ──► Assigned ──► InProgress ──► Resolved ──► Closed
 *    │          └──────────────┴───────────► Closed
 *    └──► Closed
 *
 * `canTransitionTo()` is the single source of truth for the guarded transitions;
 * assignment is handled separately (it sets the assignee and moves Open → Assigned).
 */
enum GrievanceStatus: string
{
    case Open = 'open';
    case Assigned = 'assigned';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';
    case Closed = 'closed';

    /**
     * @return list<self>
     */
    public function allowedNext(): array
    {
        return match ($this) {
            self::Open => [self::Assigned, self::Closed],
            self::Assigned => [self::InProgress, self::Resolved, self::Closed],
            self::InProgress => [self::Resolved, self::Closed],
            self::Resolved => [self::Closed],
            self::Closed => [],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedNext(), true);
    }

    public function isTerminal(): bool
    {
        return $this->allowedNext() === [];
    }
}
