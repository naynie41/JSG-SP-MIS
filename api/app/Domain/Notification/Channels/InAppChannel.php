<?php

declare(strict_types=1);

namespace App\Domain\Notification\Channels;

use App\Domain\Access\Models\User;
use App\Domain\Notification\Contracts\NotificationChannel;
use App\Domain\Notification\Models\Notification;
use App\Domain\Notification\Support\NotificationMessage;

/**
 * Persists the notification so the recipient sees it in their in-app inbox
 * (PRD FR-NOT-01). This is the system of record and is always available.
 */
class InAppChannel implements NotificationChannel
{
    public function key(): string
    {
        return 'in_app';
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function send(NotificationMessage $message, User $recipient): void
    {
        Notification::create([
            'recipient_user_id' => $recipient->id,
            'recipient_mda_id' => $recipient->mda_id,
            'type' => $message->type,
            'subject' => $message->subject,
            'body' => $message->body,
            'payload' => $message->payload === [] ? null : $message->payload,
            'related_type' => $message->relatedType,
            'related_id' => $message->relatedId,
        ]);
    }
}
