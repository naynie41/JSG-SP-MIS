<?php

declare(strict_types=1);

namespace App\Domain\Notification\Channels;

use App\Domain\Access\Models\User;
use App\Domain\Notification\Contracts\NotificationChannel;
use App\Domain\Notification\Mail\NotificationMail;
use App\Domain\Notification\Support\NotificationMessage;
use Illuminate\Support\Facades\Mail;

/**
 * Sends the notification as a QUEUED email via the configured SMTP mailer
 * (PRD FR-NOT-01). Enabled by config (`notifications.channels.email.enabled`);
 * turning it off makes the channel inert without touching the subscriber.
 */
class EmailChannel implements NotificationChannel
{
    public function key(): string
    {
        return 'email';
    }

    public function isAvailable(): bool
    {
        return (bool) config('notifications.channels.email.enabled', true);
    }

    public function send(NotificationMessage $message, User $recipient): void
    {
        Mail::to($recipient->email)->queue(new NotificationMail($message, $recipient->name));
    }
}
