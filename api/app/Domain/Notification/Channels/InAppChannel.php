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
            // The deep link travels with the notification so the bell and the email agree
            // on where the work is. `linkFor()` in the SPA still resolves the role-aware
            // destination; this is the fallback when an event names one explicitly.
            'payload' => $this->payloadFor($message),
            'related_type' => $message->relatedType,
            'related_id' => $message->relatedId,
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function payloadFor(NotificationMessage $message): ?array
    {
        $payload = $message->payload;

        if ($message->actionPath !== null) {
            $payload['action_path'] = $message->actionPath;
            $payload['action_label'] = $message->actionLabel;
        }

        return $payload === [] ? null : $payload;
    }
}
