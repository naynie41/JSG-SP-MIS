<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Programme\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Activity
 */
class ActivityResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'programme_id' => $this->programme_id,
            'owner_mda_id' => $this->owner_mda_id,
            'name' => $this->name,
            'description' => $this->description,
            'target_count' => $this->target_count,
            'lga' => $this->lga,
            'ward' => $this->ward,
            'location_description' => $this->location_description,
            'schedule' => $this->schedule,
            'starts_on' => $this->starts_on?->toDateString(),
            'ends_on' => $this->ends_on?->toDateString(),
            'budget_amount' => $this->budget_amount, // minor units (kobo, NGN)
            'status' => $this->status->value,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
