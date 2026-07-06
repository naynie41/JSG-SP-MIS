<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Services;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Enums\VerificationMethod;
use App\Domain\Benefit\Exceptions\DeliveryNotAuthorizedException;
use App\Domain\Benefit\Exceptions\NotEnrolledException;
use App\Domain\Benefit\Exceptions\VerificationUnavailableException;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Enums\EnrollmentStatus;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Support\Facades\DB;

/**
 * Records benefit DELIVERIES to the ledger (PRD FR-BEN-01/02, §8.3) and applies
 * verification (FR-BEN-04). This never moves money — it appends descriptive
 * records. The delivering MDA is the programme owner; when it does NOT own the
 * beneficiary, delivery is authorized ONLY by an explicit accepted authorization —
 * a Service Request (R2.3) or Referral (Phase 5, FR-BEN-06), never a generic seam.
 * Delivery also requires an open enrollment (§8.3).
 */
class BenefitRecorder
{
    public function __construct(
        private readonly VerifierRegistry $verifiers,
        private readonly DoubleDippingDetector $doubleDipping,
        private readonly DeliveryAuthorization $authorization,
        private readonly AuditLogger $audit,
    ) {}

    /**
     * @param  array<string, mixed>  $fields  benefit_type, quantity, unit, monetary_value, funding_source, delivery_date, notes, verification_method, verification_reference
     *
     * @throws DeliveryNotAuthorizedException
     * @throws NotEnrolledException
     * @throws VerificationUnavailableException
     */
    public function record(Beneficiary $beneficiary, Programme $programme, ?string $activityId, User $actor, array $fields): Benefit
    {
        // A non-owner MDA may deliver ONLY with an accepted Service Request /
        // Referral authorization (FR-BEN-06). Checked before enrollment so an
        // unauthorized cross-MDA attempt gets a clear refusal.
        $basis = $this->authorization->basisFor($programme->owner_mda_id, $beneficiary);
        if ($basis === null) {
            throw new DeliveryNotAuthorizedException(
                'Recording an intervention for a beneficiary this MDA does not own requires an accepted service request or referral.',
            );
        }

        $enrollment = Enrollment::query()
            ->withoutGlobalScope(MdaScope::class)
            ->where('programme_id', $programme->id)
            ->where('beneficiary_id', $beneficiary->id)
            ->where('status', EnrollmentStatus::Enrolled)
            ->first();

        if ($enrollment === null) {
            throw new NotEnrolledException('The beneficiary is not enrolled in this programme.');
        }

        $method = $fields['verification_method'] ?? VerificationMethod::None;

        return DB::transaction(function () use ($beneficiary, $programme, $activityId, $actor, $fields, $enrollment, $method, $basis): Benefit {
            $benefit = Benefit::create([
                'beneficiary_id' => $beneficiary->id,
                'programme_id' => $programme->id,
                'activity_id' => $activityId,
                'enrollment_id' => $enrollment->id,
                'mda_id' => $programme->owner_mda_id, // the delivering MDA
                'benefit_type' => $fields['benefit_type'],
                'quantity' => $fields['quantity'] ?? null,
                'unit' => $fields['unit'] ?? null,
                'monetary_value' => $fields['monetary_value'] ?? null,
                'funding_source' => $fields['funding_source'] ?? null,
                'delivery_date' => $fields['delivery_date'],
                // Denormalised recipient location for join-free coverage aggregation.
                'lga' => $beneficiary->lga,
                'ward' => $beneficiary->ward,
                'notes' => $fields['notes'] ?? null,
                'status' => BenefitStatus::Recorded,
                'verification_method' => VerificationMethod::None,
                'recorded_by' => $actor->id,
            ]);

            // Verify inline when requested. An unavailable method throws here and
            // the whole record is rolled back — nothing is persisted unverified.
            if ($method !== VerificationMethod::None) {
                $this->applyVerification($benefit, $method, $fields['verification_reference'] ?? null, $actor);
            }

            // Double-dipping detection (FR-BEN-05) — flags only, never blocks.
            $this->doubleDipping->check($benefit);

            // Audit the cross-MDA authorization basis (FR-BEN-06) when a non-owner
            // MDA delivered — ownership is unchanged; the grant only authorized serving.
            if ($basis !== 'owner') {
                $this->audit->record('benefit.delivery_authorized', $benefit, after: [
                    'basis' => $basis,
                    'delivering_mda_id' => $programme->owner_mda_id,
                    'beneficiary_owner_mda_id' => $beneficiary->owner_mda_id,
                ], actor: $actor);
            }

            return $benefit->fresh();
        });
    }

    /**
     * Verify an already-recorded delivery (FR-BEN-04).
     *
     * @throws VerificationUnavailableException
     */
    public function verify(Benefit $benefit, VerificationMethod $method, ?string $reference, User $actor): Benefit
    {
        $this->applyVerification($benefit, $method, $reference, $actor);

        return $benefit->fresh();
    }

    private function applyVerification(Benefit $benefit, VerificationMethod $method, ?string $reference, User $actor): void
    {
        $storedReference = $this->verifiers->for($method)->verify($benefit, $reference, $actor);

        $benefit->update([
            'status' => BenefitStatus::Verified,
            'verification_method' => $method,
            'verification_reference' => $storedReference,
            'verified_by' => $actor->id,
            'verified_at' => now(),
        ]);
    }
}
