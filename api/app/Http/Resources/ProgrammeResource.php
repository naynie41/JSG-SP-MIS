<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Programme\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Programme
 */
class ProgrammeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner_mda_id' => $this->owner_mda_id,
            'owner_mda' => $this->whenLoaded('ownerMda', fn () => ['id' => $this->ownerMda->id, 'name' => $this->ownerMda->name]),
            'name' => $this->name,
            'objective' => $this->objective,
            'type' => $this->type->value,
            'eligibility' => $this->eligibility ?? [],
            'enforce_eligibility' => $this->enforce_eligibility,
            'funding_source' => $this->funding_source,
            'budget_amount' => $this->budget_amount, // minor units (kobo, NGN)
            'starts_on' => $this->starts_on?->toDateString(),
            'ends_on' => $this->ends_on?->toDateString(),
            'status' => $this->status->value,
            'activities_count' => $this->whenCounted('activities'),
            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
