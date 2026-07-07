<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Enums\OwnershipTransferStatus;
use App\Domain\Registry\Events\OwnershipTransferRequested;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\OwnershipTransferRequest;
use DomainException;
use Illuminate\Support\Facades\DB;

/**
 * Ownership transfer workflow (PRD FR-OWN-05): a non-owner requests ownership,
 * the current owner approves/rejects. Every step is audited (the request/decision
 * via the Auditable model; the ownership change via beneficiary.updated plus an
 * explicit ownership_transferred entry).
 */
class OwnershipTransferService
{
    public function __construct(private readonly AuditLogger $audit) {}

    public function request(Beneficiary $beneficiary, string $toMdaId, User $requestedBy, ?string $reason = null): OwnershipTransferRequest
    {
        if ($toMdaId === $beneficiary->owner_mda_id) {
            throw new DomainException('The beneficiary is already owned by that MDA.');
        }

        $transfer = OwnershipTransferRequest::create([
            'beneficiary_id' => $beneficiary->id,
            'from_mda_id' => $beneficiary->owner_mda_id,
            'to_mda_id' => $toMdaId,
            'status' => OwnershipTransferStatus::Pending,
            'reason' => $reason,
            'requested_by' => $requestedBy->id,
        ]);

        // Notify the current owner MDA's approvers (FR-NOT-01).
        OwnershipTransferRequested::dispatch($transfer, $requestedBy);

        return $transfer;
    }

    public function approve(OwnershipTransferRequest $transfer, User $decidedBy): OwnershipTransferRequest
    {
        $this->assertPending($transfer);

        return DB::transaction(function () use ($transfer, $decidedBy): OwnershipTransferRequest {
            $beneficiary = Beneficiary::withoutGlobalScope(MdaScope::class)->findOrFail($transfer->beneficiary_id);
            $fromMdaId = $beneficiary->owner_mda_id;

            // Change ownership (audited as beneficiary.updated via Auditable).
            $beneficiary->owner_mda_id = $transfer->to_mda_id;
            $beneficiary->save();

            $transfer->update([
                'status' => OwnershipTransferStatus::Approved,
                'decided_by' => $decidedBy->id,
                'decided_at' => now(),
            ]);

            // Explicit, semantic audit of the ownership change.
            $this->audit->record(
                'beneficiary.ownership_transferred',
                $beneficiary,
                before: ['owner_mda_id' => $fromMdaId],
                after: ['owner_mda_id' => $transfer->to_mda_id],
                actor: $decidedBy,
            );

            return $transfer;
        });
    }

    public function reject(OwnershipTransferRequest $transfer, User $decidedBy, ?string $reason = null): OwnershipTransferRequest
    {
        $this->assertPending($transfer);

        $transfer->update([
            'status' => OwnershipTransferStatus::Rejected,
            'decided_by' => $decidedBy->id,
            'decided_at' => now(),
            'decision_reason' => $reason,
        ]);

        return $transfer;
    }

    private function assertPending(OwnershipTransferRequest $transfer): void
    {
        if ($transfer->status !== OwnershipTransferStatus::Pending) {
            throw new DomainException('This transfer request has already been decided.');
        }
    }
}
