<?php

declare(strict_types=1);

namespace App\Domain\Notification\Channels;

use App\Domain\Access\Models\User;
use App\Domain\Audit\Services\AuditLogger;
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
    public function __construct(private readonly AuditLogger $audit) {}

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

        /*
         * Log the send (FR-AUD-01). Personal data left this system over a channel we do
         * not control, so "who was emailed about what, and when" has to be answerable
         * later — by an auditor, or by an officer who says they were never told.
         *
         * The entry names the recipient by ID and the message by TYPE. Not the email
         * address, not the subject, not the body: an audit trail that quotes the content
         * of a notification would re-create in the log the very disclosure the email
         * itself was careful to avoid (CLAUDE.md §8).
         *
         * The ACTOR defaults to whoever is authenticated — the officer whose action
         * fired the event — while the recipient is a different person entirely, recorded
         * in the payload. Both halves matter: who caused the disclosure, and who received it.
         *
         * "Queued", not "delivered": this records handing the mail to the queue, which is
         * the last moment this process can honestly speak to. What the SMTP relay did
         * afterwards is the mail server's story to tell.
         */
        $this->audit->record(
            'notification.email_queued',
            after: [
                'recipient_user_id' => $recipient->id,
                'recipient_mda_id' => $recipient->mda_id,
                'notification_type' => $message->type,
            ],
            // Anchored to the record the notification is ABOUT, so "what was sent about
            // this service request" is one query rather than a scan of every send.
            context: array_filter([
                'entity_type' => $message->relatedType,
                'entity_id' => $message->relatedId,
            ], static fn (?string $value): bool => $value !== null),
        );
    }
}
