<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Services\ActivityLocationService;
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
            'involves_beneficiaries' => $this->involves_beneficiaries,
            'name' => $this->name,
            'description' => $this->description,
            'target_beneficiaries' => $this->target_beneficiaries,
            // The declared location set, grouped LGA → wards (replaces the old single
            // lga/ward pair). `whole_lga` marks an LGA declared without specific wards.
            'locations' => app(ActivityLocationService::class)->present($this->resource),
            'location_description' => $this->location_description,
            'schedule' => $this->schedule,
            'starts_on' => $this->starts_on?->toDateString(),
            'ends_on' => $this->ends_on?->toDateString(),
            'budget_amount' => $this->budget_amount, // minor units (kobo, NGN)
            'funding_source' => $this->funding_source,
            'funding_partner_id' => $this->funding_partner_id,
            'status' => $this->status->value,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
