<?php

declare(strict_types=1);

namespace App\Domain\Notification\Listeners;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Notification\Services\Notifier;
use App\Domain\Notification\Support\NotificationMessage;
use App\Domain\Registry\Events\OwnershipTransferRequested;
use App\Domain\Registry\Events\ServiceRequestAccepted;
use App\Domain\Registry\Events\ServiceRequestDeclined;
use App\Domain\Registry\Events\ServiceRequestRaised;
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
        ];
    }
}
