<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Registry\Models\HouseholdMembership;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin HouseholdMembership
 */
class HouseholdMembershipResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'household_id' => $this->household_id,
            'beneficiary_id' => $this->beneficiary_id,
            'beneficiary_name' => $this->whenLoaded('beneficiary', fn () => $this->beneficiary->fullName()),
            'role_in_household' => $this->role_in_household->value,
            'joined_at' => $this->joined_at->toIso8601String(),
            'left_at' => $this->left_at?->toIso8601String(),
            'is_open' => $this->left_at === null,
        ];
    }
}
