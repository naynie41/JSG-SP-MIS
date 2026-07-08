<?php

declare(strict_types=1);

namespace App\Domain\Notification\Listeners;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Grievance\Events\GrievanceAssigned;
use App\Domain\Grievance\Events\GrievanceResolved;
use App\Domain\Grievance\Events\GrievanceSlaBreached;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Notification\Services\Notifier;
use App\Domain\Notification\Support\NotificationMessage;
use App\Domain\Referral\Events\ReferralSlaBreached;
use App\Domain\Referral\Events\ReferralStatusChanged;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Events\OwnershipTransferRequested;
use App\Domain\Registry\Events\ServiceRequestAccepted;
use App\Domain\Registry\Events\ServiceRequestDeclined;
use App\Domain\Registry\Events\ServiceRequestRaised;
use App\Domain\Reporting\Events\ReportReady;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;

/**
 * Turns domain events into notifications (PRD FR-NOT-01), mirroring the
 * AuditEventSubscriber pattern. It resolves recipients (approvers in an MDA, or the
 * original requester) and hands a channel-agnostic message to the {@see Notifier}.
 * Bodies carry NO beneficiary PII — only a reference to the originating entity.
 */
class NotificationSubscriber
{
    public function __construct(private readonly Notifier $notifier) {}

    public function handleServiceRequestRaised(ServiceRequestRaised $event): void
    {
        // The owner MDA's approvers decide the request.
        $this->notifier->notify(
            new NotificationMessage(
                type: 'service_request.created',
                subject: 'New service request to review',
                body: 'Another MDA has requested to serve a beneficiary your MDA owns.',
                related: $event->request,
            ),
            $this->approversIn($event->request->to_mda_id, 'beneficiary.approve'),
        );
    }

    public function handleServiceRequestAccepted(ServiceRequestAccepted $event): void
    {
        $this->notifier->notify(
            new NotificationMessage(
                type: 'service_request.accepted',
                subject: 'Service request accepted',
                body: 'Your service request was accepted — your MDA may now serve this beneficiary.',
                related: $event->request,
            ),
            $this->requester($event->request->requested_by, $event->request->from_mda_id),
        );
    }

    public function handleServiceRequestDeclined(ServiceRequestDeclined $event): void
    {
        $this->notifier->notify(
            new NotificationMessage(
                type: 'service_request.declined',
                subject: 'Service request declined',
                body: 'Your service request was declined'.($event->request->decision_reason !== null ? ': '.$event->request->decision_reason : '.'),
                related: $event->request,
            ),
            $this->requester($event->request->requested_by, $event->request->from_mda_id),
        );
    }

    public function handleOwnershipTransferRequested(OwnershipTransferRequested $event): void
    {
        // The CURRENT owner (from_mda_id) approves an ownership transfer.
        $this->notifier->notify(
            new NotificationMessage(
                type: 'ownership_transfer.requested',
                subject: 'Ownership transfer requested',
                body: 'Another MDA has requested ownership of a beneficiary your MDA owns.',
                related: $event->transfer,
            ),
            $this->approversIn($event->transfer->from_mda_id, 'beneficiary.approve'),
        );
    }

    /**
     * Users in an MDA holding a permission. Bypasses the MDA scope because the
     * actor firing the event is usually in a DIFFERENT MDA than the recipients.
     *
     * @return Collection<int, User>
     */
    private function approversIn(string $mdaId, string $permission): Collection
    {
        return User::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('mda_id', $mdaId)
            ->get()
            ->filter(fn (User $user): bool => $user->hasPermission($permission))
            ->values();
    }

    /**
     * The requester to notify: the original requesting user if known, otherwise the
     * requesting MDA's users who can raise/act on requests.
     *
     * @return Collection<int, User>
     */
    private function requester(?string $requestedBy, string $fromMdaId): Collection
    {
        if ($requestedBy !== null) {
            $user = User::query()->withoutGlobalScope(MdaScope::class)->find($requestedBy);
            if ($user !== null) {
                return new Collection([$user]);
            }
        }

        return $this->approversIn($fromMdaId, 'beneficiary.create');
    }

    public function handleReferralStatusChanged(ReferralStatusChanged $event): void
    {
        // Both MDAs are informed of every transition (FR-REF-05).
        $this->notifier->notify(
            new NotificationMessage(
                type: 'referral.'.$event->action,
                subject: 'Referral '.str_replace('_', ' ', $event->action),
                body: 'A referral involving your MDA is now '.$event->referral->status->value.'.',
                related: $event->referral,
            ),
            $this->bothParties($event->referral),
        );
    }

    public function handleReferralSlaBreached(ReferralSlaBreached $event): void
    {
        // Notify both MDAs AND the escalation tier for this level (FR-REF-04/05).
        $recipients = $this->bothParties($event->referral)
            ->merge($this->escalationTier($event->referral, $event->level))
            ->unique('id')
            ->values();

        $this->notifier->notify(
            new NotificationMessage(
                type: 'referral.sla_breached',
                subject: 'Referral overdue — escalation level '.$event->level,
                body: 'A referral in status '.$event->referral->status->value.' has breached its SLA and was escalated.',
                payload: ['escalation_level' => $event->level],
                related: $event->referral,
            ),
            $recipients,
        );
    }

    /**
     * Referral-handling users in BOTH the originating and receiving MDAs.
     *
     * @return Collection<int, User>
     */
    private function bothParties(Referral $referral): Collection
    {
        return $this->approversIn($referral->from_mda_id, 'referral.edit')
            ->merge($this->approversIn($referral->to_mda_id, 'referral.edit'))
            ->unique('id')
            ->values();
    }

    /**
     * The escalation-chain tier for a breach level: users holding the tier's role.
     * Roles are organizational (e.g. coordination) so they are resolved globally.
     *
     * @return Collection<int, User>
     */
    private function escalationTier(Referral $referral, int $level): Collection
    {
        /** @var list<string> $chain */
        $chain = (array) config('sla.referral.escalation_chain', []);
        if ($chain === []) {
            return new Collection;
        }

        $roleKey = $chain[min($level - 1, count($chain) - 1)];

        return User::query()
            ->withoutGlobalScope(MdaScope::class)
            ->whereHas('role', fn ($query) => $query->where('key', $roleKey))
            ->get();
    }

    public function handleGrievanceAssigned(GrievanceAssigned $event): void
    {
        // The assignee is notified (FR-GRM-02).
        $this->notifier->notify(
            new NotificationMessage(
                type: 'grievance.assigned',
                subject: 'A grievance was assigned to you',
                body: 'You have been assigned a grievance ('.$event->grievance->category->value.') to handle.',
                related: $event->grievance,
            ),
            new Collection([$event->assignee]),
        );
    }

    public function handleGrievanceResolved(GrievanceResolved $event): void
    {
        // Relevant parties: the handling MDA's grievance team + whoever logged it.
        $recipients = $this->approversIn($event->grievance->handling_mda_id, 'grievance.edit');
        if ($event->grievance->submitted_by !== null) {
            $submitter = User::query()->withoutGlobalScope(MdaScope::class)->find($event->grievance->submitted_by);
            if ($submitter !== null) {
                $recipients = $recipients->push($submitter);
            }
        }

        $this->notifier->notify(
            new NotificationMessage(
                type: 'grievance.resolved',
                subject: 'Grievance resolved',
                body: 'A grievance ('.$event->grievance->category->value.') has been resolved.',
                related: $event->grievance,
            ),
            $recipients->unique('id')->values(),
        );
    }

    public function handleGrievanceSlaBreached(GrievanceSlaBreached $event): void
    {
        // The handling MDA's grievance team AND the escalation tier for this level
        // are alerted (FR-GRM-03). Nothing is auto-closed.
        $recipients = $this->approversIn($event->grievance->handling_mda_id, 'grievance.edit')
            ->merge($this->grievanceEscalationTier($event->grievance, $event->level))
            ->unique('id')
            ->values();

        $this->notifier->notify(
            new NotificationMessage(
                type: 'grievance.sla_breached',
                subject: 'Grievance overdue — escalation level '.$event->level,
                body: 'A grievance ('.$event->grievance->category->value.') in status '.$event->grievance->status->value.' has breached its SLA and was escalated.',
                payload: ['escalation_level' => $event->level],
                related: $event->grievance,
            ),
            $recipients,
        );
    }

    /**
     * The escalation-chain tier for a grievance breach level. Grievances are handled
     * within one MDA, so the tier is resolved WITHIN the handling MDA first (e.g. its
     * admins), falling back to an org-wide role (e.g. coordination) if that role has
     * no member in the handling MDA.
     *
     * @return Collection<int, User>
     */
    private function grievanceEscalationTier(Grievance $grievance, int $level): Collection
    {
        /** @var list<string> $chain */
        $chain = (array) config('sla.grievance.escalation_chain', []);
        if ($chain === []) {
            return new Collection;
        }

        $roleKey = $chain[min($level - 1, count($chain) - 1)];

        $inHandlingMda = User::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('mda_id', $grievance->handling_mda_id)
            ->whereHas('role', fn ($query) => $query->where('key', $roleKey))
            ->get();

        if ($inHandlingMda->isNotEmpty()) {
            return $inHandlingMda;
        }

        return User::query()
            ->withoutGlobalScope(MdaScope::class)
            ->whereHas('role', fn ($query) => $query->where('key', $roleKey))
            ->get();
    }

    public function handleReportReady(ReportReady $event): void
    {
        // The user who generated the report is notified it can be downloaded (FR-RPT-03).
        $run = $event->run;
        if ($run->requested_by === null) {
            return;
        }

        $user = User::query()->withoutGlobalScope(MdaScope::class)->find($run->requested_by);
        if ($user === null) {
            return;
        }

        $this->notifier->notify(
            new NotificationMessage(
                type: 'report.ready',
                subject: 'Your report is ready',
                body: 'The "'.$run->report_label.'" report you generated ('.strtoupper($run->format).') is ready to download.',
                payload: ['format' => $run->format, 'report_key' => $run->report_key],
                related: $run,
            ),
            new Collection([$user]),
        );
    }

    /**
     * @return array<class-string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            ServiceRequestRaised::class => 'handleServiceRequestRaised',
            ServiceRequestAccepted::class => 'handleServiceRequestAccepted',
            ServiceRequestDeclined::class => 'handleServiceRequestDeclined',
            OwnershipTransferRequested::class => 'handleOwnershipTransferRequested',
            ReferralStatusChanged::class => 'handleReferralStatusChanged',
            ReferralSlaBreached::class => 'handleReferralSlaBreached',
            GrievanceAssigned::class => 'handleGrievanceAssigned',
            GrievanceResolved::class => 'handleGrievanceResolved',
            GrievanceSlaBreached::class => 'handleGrievanceSlaBreached',
            ReportReady::class => 'handleReportReady',
        ];
    }
}
