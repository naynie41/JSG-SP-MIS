<?php

declare(strict_types=1);

namespace App\Domain\Notification\Mail;

use App\Domain\Notification\Support\NotificationMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * The email rendering of a notification (PRD FR-NOT-01). Queued (rabbitmq) so
 * sending never blocks the request. Uses the SMTP config in `config/mail.php`
 * (`MAIL_MAILER=log` in dev). Plain HTML — no PII beyond the subject/body the
 * subscriber composed.
 */
class NotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly NotificationMessage $message,
        public readonly string $recipientName,
    ) {}

    public function build(): self
    {
        $line = e($this->message->body ?? $this->message->subject);
        $name = e($this->recipientName);
        $html = "<p>Hello {$name},</p><p>{$line}</p><p>— Jigawa State SP-MIS</p>";

        return $this->subject($this->message->subject)->html($html);
    }
}
