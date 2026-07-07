<?php

declare(strict_types=1);

namespace App\Domain\Referral\Enums;

/**
 * Referral lifecycle (PRD FR-REF-02/04, §8.2):
 *
 *   Created ──► Accepted ──► InProgress ──► Completed | Closed
 *      │           └───────────────────────► Closed
 *      ├──► Rejected            (terminal, reason required)
 *      └──► MoreInfoRequested ──► Created   (originating MDA responds)
 *
 * The receiving MDA drives accept/reject/request-info and the working states;
 * the originating MDA responds to a more-info request. `canTransitionTo()` is the
 * single source of truth for valid transitions.
 */
enum ReferralStatus: string
{
    case Created = 'created';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case MoreInfoRequested = 'more_info_requested';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Closed = 'closed';

    /**
     * @return list<self>
     */
    public function allowedNext(): array
    {
        return match ($this) {
            self::Created => [self::Accepted, self::Rejected, self::MoreInfoRequested],
            self::MoreInfoRequested => [self::Created],
            self::Accepted => [self::InProgress, self::Closed],
            self::InProgress => [self::Completed, self::Closed],
            self::Rejected, self::Completed, self::Closed => [],
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
