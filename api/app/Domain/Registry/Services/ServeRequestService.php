<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Access\Models\User;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Enums\ServeRequestStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ServeRequest;
use DomainException;
use Illuminate\Support\Carbon;

/**
 * Request-to-serve workflow (PRD FR-DUP-05). A requesting MDA asks the owner MDA
 * to serve an existing beneficiary; the owner accepts or declines. Ownership is
 * NEVER changed — this only grants serve access on acceptance. Every step is
 * audited (request/decision via Auditable + explicit decision entries).
 */
class ServeRequestService
{
    public function __construct(private readonly AuditLogger $audit) {}

    /**
     * Raise a serve request against a beneficiary owned by another MDA. Idempotent
     * on the pending state: a second request for the same (beneficiary, MDA)
     * returns the existing pending one.
     */
    public function request(Beneficiary $beneficiary, string $fromMdaId, ?User $requestedBy, ?string $reason = null, ?string $importRowId = null): ServeRequest
    {
        if ($fromMdaId === $beneficiary->owner_mda_id) {
            throw new DomainException('An MDA cannot request to serve a beneficiary it already owns.');
        }

        $existing = ServeRequest::query()
            ->where('beneficiary_id', $beneficiary->id)
            ->where('from_mda_id', $fromMdaId)
            ->where('status', ServeRequestStatus::Pending)
            ->first();
        if ($existing !== null) {
            return $existing;
        }

        return ServeRequest::create([
            'beneficiary_id' => $beneficiary->id,
            'from_mda_id' => $fromMdaId,
            'to_mda_id' => $beneficiary->owner_mda_id, // routed to the owner MDA
            'status' => ServeRequestStatus::Pending,
            'reason' => $reason,
            'import_row_id' => $importRowId,
            'requested_by' => $requestedBy?->id,
        ]);
    }

    /** Owner accepts — the requesting MDA gains serve access. Ownership unchanged. */
    public function accept(ServeRequest $request, User $decidedBy, ?string $reason = null): ServeRequest
    {
        return $this->decide($request, ServeRequestStatus::Accepted, $decidedBy, $reason);
    }

    public function decline(ServeRequest $request, User $decidedBy, ?string $reason = null): ServeRequest
    {
        return $this->decide($request, ServeRequestStatus::Declined, $decidedBy, $reason);
    }

    private function decide(ServeRequest $request, ServeRequestStatus $status, User $decidedBy, ?string $reason): ServeRequest
    {
        if ($request->status !== ServeRequestStatus::Pending) {
            throw new DomainException('This serve request has already been decided.');
        }

        $request->update([
            'status' => $status,
            'decided_by' => $decidedBy->id,
            'decided_at' => Carbon::now(),
            'decision_reason' => $reason,
        ]);

        $this->audit->record('serve_request.'.$status->value, $request, after: [
            'status' => $status->value,
            'beneficiary_id' => $request->beneficiary_id,
        ], actor: $decidedBy);

        return $request;
    }
}
