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
 * (`MAIL_MAILER=log` in dev).
 *
 * **Email is not a secure channel** (NDPA/NDPR minimisation). It sits on third-party
 * relays, in inboxes on unmanaged devices, and in backups nobody in this system
 * controls. So the body carries only what the subscriber composed — an event, a
 * requesting organisation, a link — and NEVER beneficiary identity: no name, no NIN,
 * no BVN, no phone. The record itself is reached by logging in, where scope, role and
 * the audit trail all still apply. Everything here is escaped; nothing is interpolated
 * from a beneficiary.
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

        $html = "<p>Hello {$name},</p><p>{$line}</p>";

        $url = $this->message->actionUrl();
        if ($url !== null) {
            // The whole point of an action-required email: somewhere to go. Rendered as
            // a link AND as plain text, because a mail client that strips the anchor
            // would otherwise leave the reader with an instruction and no address.
            $label = e($this->message->actionLabel ?? 'Open SP-MIS');
            $safeUrl = e($url);
            $html .= "<p><a href=\"{$safeUrl}\">{$label}</a></p>"
                .'<p style="color:#555;font-size:12px">If the link does not open, paste this into your browser:<br>'
                ."{$safeUrl}</p>";
        }

        $html .= '<p>— Jigawa State SP-MIS</p>'.$this->footer();

        return $this->subject($this->message->subject)->html($html);
    }

    /**
     * How to stop receiving these. Preferences ARE the unsubscribe mechanism
     * (FR-NOT-02): these are operational notices to named officers about their own
     * caseload, not marketing, so the control lives behind their login rather than on a
     * public one-click token endpoint — a URL that mutates a user's settings without
     * authentication is a surface this system does not need.
     */
    private function footer(): string
    {
        $url = e(
            rtrim((string) config('notifications.app_url'), '/')
            .'/'.ltrim((string) config('notifications.preferences_path'), '/')
        );

        return '<hr><p style="color:#555;font-size:12px">'
            .'You are receiving this because your SP-MIS account is set to get email notifications. '
            ."You can turn them off in <a href=\"{$url}\">your notification preferences</a>.</p>";
    }
}
