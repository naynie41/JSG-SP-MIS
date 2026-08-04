<?php

declare(strict_types=1);

namespace App\Domain\Notification\Services;

use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Notification\Support\NotificationMessage;

/**
 * System-wide announcements from the administration console (PRD FR-NOT-01).
 *
 * This is not a second notification path: it builds one {@see NotificationMessage} and
 * hands it to the existing {@see Notifier}, so channel availability and each
 * recipient's preferences apply exactly as they do for a domain event. In-app is
 * always delivered — it is the inbox.
 *
 * Audience is resolved from live records: ACTIVE users only, optionally narrowed to a
 * role or an MDA. The broadcast itself is audited (audience filters + recipient count),
 * never its recipients individually.
 */
class BroadcastService
{
    public function __construct(
        private readonly Notifier $notifier,
        private readonly AuditLogger $audit,
    ) {}

    /**
     * @param  array{role_key?: string|null, mda_id?: string|null}  $audience
     * @return int the number of recipients the message was fanned out to
     */
    public function send(string $subject, ?string $body, array $audience, User $actor): int
    {
        $roleKey = $audience['role_key'] ?? null;
        $mdaId = $audience['mda_id'] ?? null;

        // Platform-wide by definition, so the request-time MDA scope is bypassed
        // explicitly rather than relying on the actor's own reach.
        $query = User::query()->withoutGlobalScope(MdaScope::class)->where('status', 'active');

        if ($roleKey !== null && $roleKey !== '') {
            $roleId = Role::query()->where('key', $roleKey)->value('id');
            $query->where('role_id', $roleId);
        }
        if ($mdaId !== null && $mdaId !== '') {
            $query->where('mda_id', $mdaId);
        }

        /** @var list<User> $recipients */
        $recipients = $query->get()->all();

        $message = new NotificationMessage(
            type: 'system.broadcast',
            subject: $subject,
            body: $body,
            payload: ['broadcast' => true, 'sent_by' => $actor->id],
        );

        $this->notifier->notify($message, $recipients);

        $this->audit->record(
            'notification.broadcast',
            null,
            after: [
                'subject' => $subject,
                'audience' => ['role_key' => $roleKey, 'mda_id' => $mdaId],
                'recipient_count' => count($recipients),
            ],
            actor: $actor,
            context: ['entity_type' => 'Broadcast'],
        );

        return count($recipients);
    }

    /** Audience size for a filter, so the console can confirm before sending. */
    public function audienceCount(?string $roleKey, ?string $mdaId): int
    {
        $query = User::query()->withoutGlobalScope(MdaScope::class)->where('status', 'active');

        if ($roleKey !== null && $roleKey !== '') {
            $query->where('role_id', Role::query()->where('key', $roleKey)->value('id'));
        }
        if ($mdaId !== null && $mdaId !== '') {
            $query->where('mda_id', $mdaId);
        }

        return (int) $query->count();
    }
}
