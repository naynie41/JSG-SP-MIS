<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Listeners;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Notification\Channels\InAppChannel;
use App\Domain\Notification\Services\Notifier;
use App\Domain\Notification\Support\NotificationMessage;
use App\Domain\Reporting\Events\ReportReady;
use App\Domain\Reporting\Mail\ScheduledReportMail;
use App\Domain\Reporting\Models\ReportSchedule;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use Illuminate\Support\Facades\Mail;

/**
 * Delivers a ready SCHEDULED report to its validated recipients (PRD FR-RPT-04). Only
 * acts on runs that carry a recipient list (a schedule produced them); on-demand runs
 * are handled by the notification subscriber. Each recipient's scope is RE-VALIDATED
 * here (defence in depth) so a role change since scheduling can never leak data:
 *
 *  - link delivery → in-app + email link via the Phase 5 Notifier (nothing leaves the
 *    system; recipients download through the permission-gated endpoint);
 *  - attachment delivery → in-app record + the file attached to an email.
 */
class DeliverScheduledReport
{
    public function __construct(
        private readonly Notifier $notifier,
        private readonly InAppChannel $inApp,
        private readonly DashboardScopeResolver $resolver,
    ) {}

    public function handle(ReportReady $event): void
    {
        $run = $event->run;
        if ($run->recipient_user_ids === null) {
            return; // on-demand report — delivered by the notification subscriber
        }

        $scope = $run->toScope();

        foreach ($run->recipient_user_ids as $recipientId) {
            $user = User::query()->withoutGlobalScope(MdaScope::class)->find($recipientId);
            if ($user === null || ! $this->resolver->forUser($user)->covers($scope)) {
                continue; // recipient no longer entitled to this scope — skip
            }

            $message = new NotificationMessage(
                type: 'report.ready',
                subject: 'Scheduled report ready',
                body: 'Your scheduled report "'.$run->report_label.'" ('.strtoupper($run->format).') is ready.',
                payload: ['format' => $run->format, 'delivery' => (string) $run->delivery],
                related: $run,
            );

            if ($run->delivery === ReportSchedule::DELIVERY_ATTACHMENT) {
                $this->inApp->send($message, $user);
                Mail::to($user->email)->queue(new ScheduledReportMail($run, $user->name));
            } else {
                $this->notifier->notify($message, [$user]);
            }
        }
    }
}
