<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Benefit\Models\Benefit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Benefit
 */
class BenefitResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'beneficiary_id' => $this->beneficiary_id,
            'programme_id' => $this->programme_id,
            'activity_id' => $this->activity_id,
            'enrollment_id' => $this->enrollment_id,
            'mda_id' => $this->mda_id, // delivering MDA
            'benefit_type' => $this->benefit_type->value,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'monetary_value' => $this->monetary_value, // minor units (kobo, NGN) — data only
            'funding_source' => $this->funding_source,
            'delivery_date' => $this->delivery_date->toDateString(),
            'lga' => $this->lga,
            'ward' => $this->ward,
            'status' => $this->status->value,
            'verification_method' => $this->verification_method->value,
            'verification_reference' => $this->verification_reference,
            'verified_by' => $this->verified_by,
            'verified_at' => $this->verified_at?->toIso8601String(),
            'recorded_by' => $this->recorded_by,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
