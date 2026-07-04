<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Benefit\Models\BenefitFlag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin BenefitFlag
 */
class BenefitFlagResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'beneficiary_id' => $this->beneficiary_id,
            'benefit_id' => $this->benefit_id,
            'related_benefit_id' => $this->related_benefit_id,
            'benefit_type' => $this->benefit_type,
            'from_mda_id' => $this->from_mda_id,
            'other_mda_id' => $this->other_mda_id,
            'status' => $this->status->value,
            'reason' => $this->reason,
            'reviewed_by' => $this->reviewed_by,
            'reviewed_at' => $this->reviewed_at?->toIso8601String(),
            'review_note' => $this->review_note,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
