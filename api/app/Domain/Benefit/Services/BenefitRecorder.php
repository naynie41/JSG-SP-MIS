<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Services;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Enums\VerificationMethod;
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
 * records. Delivery requires an open enrollment (which is what granted the serve
 * relationship for a non-owned beneficiary); the delivering MDA is the programme
 * owner.
 */
class BenefitRecorder
{
    public function __construct(
        private readonly VerifierRegistry $verifiers,
        private readonly DoubleDippingDetector $doubleDipping,
    ) {}

    /**
     * @param  array<string, mixed>  $fields  benefit_type, quantity, unit, monetary_value, funding_source, delivery_date, notes, verification_method, verification_reference
     *
     * @throws NotEnrolledException
     * @throws VerificationUnavailableException
     */
    public function record(Beneficiary $beneficiary, Programme $programme, ?string $activityId, User $actor, array $fields): Benefit
    {
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

        return DB::transaction(function () use ($beneficiary, $programme, $activityId, $actor, $fields, $enrollment, $method): Benefit {
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
