<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Registry\Models\Household;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Household view for the owner MDA. Current members are included when the
 * `currentMemberships` relation is loaded; full history is exposed via the
 * membership endpoints. JSON keys are snake_case per CONVENTIONS.md.
 *
 * @mixin Household
 */
class HouseholdResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner_mda_id' => $this->owner_mda_id,
            'head_beneficiary_id' => $this->head_beneficiary_id,
            'registration_source' => $this->registration_source->value,
            'registration_date' => $this->registration_date->toDateString(),
            'address' => $this->address,
            'lga' => $this->lga,
            'ward' => $this->ward,
            'members' => HouseholdMembershipResource::collection($this->whenLoaded('currentMemberships')),
            // Full membership history (open + closed) for the detail view.
            'history' => HouseholdMembershipResource::collection($this->whenLoaded('memberships')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
