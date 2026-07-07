<?php

declare(strict_types=1);

namespace App\Domain\Referral\Services;

use App\Domain\Access\Models\User;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Referral\Enums\ReferralStatus;
use App\Domain\Referral\Exceptions\InvalidReferralTransitionException;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Support\Carbon;

/**
 * Referral creation + lifecycle (PRD FR-REF-01/02/04, §8.2). Every transition is
 * guarded by {@see ReferralStatus::canTransitionTo()}, timestamped on its own
 * column, and audited explicitly (referral.accepted, referral.rejected, …). The
 * WHO (receiving vs originating MDA) is enforced by the policy; this service owns
 * the WHAT (valid state machine).
 */
class ReferralService
{
    public function __construct(private readonly AuditLogger $audit) {}

    /** Originating MDA creates a referral to another MDA. */
    public function create(Beneficiary $beneficiary, string $toMdaId, User $createdBy, string $need, ?string $notes): Referral
    {
        if ($createdBy->mda_id === $toMdaId) {
            throw new InvalidReferralTransitionException('An MDA cannot refer a beneficiary to itself.');
        }

        // Auditable records referral.created.
        return Referral::create([
            'beneficiary_id' => $beneficiary->id,
            'from_mda_id' => $createdBy->mda_id,
            'to_mda_id' => $toMdaId,
            'need' => $need,
            'notes' => $notes,
            'status' => ReferralStatus::Created,
            'created_by' => $createdBy->id,
        ]);
    }

    public function accept(Referral $referral, User $actor): Referral
    {
        return $this->transition($referral, ReferralStatus::Accepted, 'accepted', $actor, ['accepted_at' => Carbon::now()]);
    }

    public function reject(Referral $referral, User $actor, string $reason): Referral
    {
        return $this->transition($referral, ReferralStatus::Rejected, 'rejected', $actor, ['rejected_at' => Carbon::now(), 'reason' => $reason]);
    }

    public function requestInfo(Referral $referral, User $actor, ?string $question): Referral
    {
        return $this->transition($referral, ReferralStatus::MoreInfoRequested, 'info_requested', $actor, ['info_requested_at' => Carbon::now(), 'info_request' => $question]);
    }

    public function respondInfo(Referral $referral, User $actor, ?string $response): Referral
    {
        return $this->transition($referral, ReferralStatus::Created, 'info_responded', $actor, ['info_responded_at' => Carbon::now(), 'info_response' => $response]);
    }

    public function start(Referral $referral, User $actor): Referral
    {
        return $this->transition($referral, ReferralStatus::InProgress, 'started', $actor, ['started_at' => Carbon::now()]);
    }

    public function complete(Referral $referral, User $actor, ?string $outcome): Referral
    {
        return $this->transition($referral, ReferralStatus::Completed, 'completed', $actor, ['completed_at' => Carbon::now(), 'outcome' => $outcome]);
    }

    public function close(Referral $referral, User $actor, ?string $outcome): Referral
    {
        return $this->transition($referral, ReferralStatus::Closed, 'closed', $actor, ['closed_at' => Carbon::now(), 'outcome' => $outcome]);
    }

    /**
     * @param  array<string, mixed>  $changes
     */
    private function transition(Referral $referral, ReferralStatus $to, string $action, User $actor, array $changes): Referral
    {
        if (! $referral->status->canTransitionTo($to)) {
            throw new InvalidReferralTransitionException(
                "A referral in '{$referral->status->value}' cannot be {$action}.",
            );
        }

        $from = $referral->status;
        $referral->update(['status' => $to, ...$changes]);

        // Explicit, timestamped audit of the transition (FR-REF-04).
        $meaningful = array_filter(
            $changes,
            static fn (string $key): bool => ! str_ends_with($key, '_at'),
            ARRAY_FILTER_USE_KEY,
        );
        $this->audit->record('referral.'.$action, $referral, before: ['status' => $from->value], after: [
            'status' => $to->value,
            'beneficiary_id' => $referral->beneficiary_id,
            ...$meaningful,
        ], actor: $actor);

        return $referral;
    }
}
