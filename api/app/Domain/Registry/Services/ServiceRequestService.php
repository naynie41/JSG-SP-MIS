<?php

declare(strict_types=1);

namespace App\Domain\Registry\Services;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Registry\Enums\ServiceRequestStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\BeneficiaryServiceGrant;
use App\Domain\Registry\Models\ServiceRequest;
use DomainException;
use Illuminate\Support\Carbon;

/**
 * Service Request workflow (PRD §12, FR-OWN-06/07). A requesting MDA asks the
 * owner MDA to serve an existing beneficiary; the owner accepts or declines.
 * State machine: pending → accepted | declined.
 *
 * - Ownership (`beneficiaries.owner_mda_id`) is NEVER changed.
 * - On ACCEPT an explicit read-access grant opens (FR-OWN-07) — READ-only access
 *   to the full record for the requesting MDA — and `beneficiary.access_granted`
 *   is audited. Interventions may be recorded only after acceptance.
 * - On DECLINE a reason is required; no grant opens.
 * - Every request/decision is audited (service_request.created via Auditable;
 *   service_request.accepted/declined explicitly).
 *
 * This is distinct from the Referral flow — it does not touch or reuse it.
 */
class ServiceRequestService
{
    public function __construct(private readonly AuditLogger $audit) {}

    /**
     * Raise a request against a beneficiary owned by another MDA. Idempotent on the
     * pending state: a second request for the same (beneficiary, MDA) returns the
     * existing pending one.
     */
    public function request(Beneficiary $beneficiary, string $fromMdaId, ?User $requestedBy, ?string $reason = null, ?string $importRowId = null): ServiceRequest
    {
        if ($fromMdaId === $beneficiary->owner_mda_id) {
            throw new DomainException('An MDA cannot request to serve a beneficiary it already owns.');
        }

        $existing = ServiceRequest::query()
            ->where('beneficiary_id', $beneficiary->id)
            ->where('from_mda_id', $fromMdaId)
            ->where('status', ServiceRequestStatus::Pending)
            ->first();
        if ($existing !== null) {
            return $existing;
        }

        // Auditable records service_request.created.
        return ServiceRequest::create([
            'beneficiary_id' => $beneficiary->id,
            'from_mda_id' => $fromMdaId,
            'to_mda_id' => $beneficiary->owner_mda_id, // routed to the owner MDA
            'status' => ServiceRequestStatus::Pending,
            'reason' => $reason,
            'import_row_id' => $importRowId,
            'requested_by' => $requestedBy?->id,
        ]);
    }

    /**
     * Owner accepts — opens a read-access grant for the requester and records it.
     * Ownership is unchanged.
     */
    public function accept(ServiceRequest $request, User $decidedBy, ?string $reason = null): ServiceRequest
    {
        $this->decide($request, ServiceRequestStatus::Accepted, $decidedBy, $reason);

        $grant = $this->openGrant($request, $decidedBy);
        if ($grant !== null) {
            $beneficiary = Beneficiary::query()->withoutGlobalScope(MdaScope::class)->find($request->beneficiary_id);
            $this->audit->record('beneficiary.access_granted', $beneficiary, after: [
                'granted_mda_id' => $request->from_mda_id,
                'service_request_id' => $request->id,
                'grant_id' => $grant->id,
                'access' => 'read',
            ], actor: $decidedBy);
        }

        return $request;
    }

    /** Owner declines — a reason is required. No grant opens. */
    public function decline(ServiceRequest $request, User $decidedBy, string $reason): ServiceRequest
    {
        return $this->decide($request, ServiceRequestStatus::Declined, $decidedBy, $reason);
    }

    /** Whether the given MDA currently holds an active read/serve grant. */
    public static function hasActiveGrant(string $beneficiaryId, string $mdaId): bool
    {
        return BeneficiaryServiceGrant::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('beneficiary_id', $beneficiaryId)
            ->where('mda_id', $mdaId)
            ->whereNull('revoked_at')
            ->exists();
    }

    private function decide(ServiceRequest $request, ServiceRequestStatus $status, User $decidedBy, ?string $reason): ServiceRequest
    {
        if ($request->status !== ServiceRequestStatus::Pending) {
            throw new DomainException('This service request has already been decided.');
        }

        $request->update([
            'status' => $status,
            'decided_by' => $decidedBy->id,
            'decided_at' => Carbon::now(),
            'decision_reason' => $reason,
        ]);

        $this->audit->record('service_request.'.$status->value, $request, after: [
            'status' => $status->value,
            'beneficiary_id' => $request->beneficiary_id,
            'decision_reason' => $reason,
        ], actor: $decidedBy);

        return $request;
    }

    /** Open (or reuse) the requester's active read-access grant. */
    private function openGrant(ServiceRequest $request, User $decidedBy): ?BeneficiaryServiceGrant
    {
        $existing = BeneficiaryServiceGrant::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('beneficiary_id', $request->beneficiary_id)
            ->where('mda_id', $request->from_mda_id)
            ->whereNull('revoked_at')
            ->first();
        if ($existing !== null) {
            return null; // already granted — nothing new to open/audit
        }

        return BeneficiaryServiceGrant::create([
            'beneficiary_id' => $request->beneficiary_id,
            'mda_id' => $request->from_mda_id,
            'service_request_id' => $request->id,
            'granted_by' => $decidedBy->id,
            'granted_at' => Carbon::now(),
        ]);
    }
}
