<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Mail;

use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Models\ReportRun;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Emails a scheduled report as an ATTACHMENT (PRD FR-RPT-04, attachment delivery). The
 * file is aggregate, de-identified data (SECURITY.md) and only sent to scope-validated
 * recipients. Queued so sending never blocks. For "link" delivery the notification
 * (in-app + email link) is used instead — no file leaves the system.
 */
class ScheduledReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly ReportRun $run,
        public readonly string $recipientName,
    ) {}

    public function build(): self
    {
        $name = e($this->recipientName);
        $label = e($this->run->report_label);
        $format = strtoupper($this->run->format);
        $html = "<p>Hello {$name},</p><p>Your scheduled report \"{$label}\" ({$format}) is attached.</p><p>— Jigawa State SP-MIS</p>";

        $mail = $this->subject('Scheduled report: '.$this->run->report_label)->html($html);

        if ($this->run->file_path !== null) {
            $mail->attachFromStorageDisk('local', $this->run->file_path, $this->run->file_name ?? 'report', [
                'mime' => ReportFormat::from($this->run->format)->mimeType(),
            ]);
        }

        return $mail;
    }
}
