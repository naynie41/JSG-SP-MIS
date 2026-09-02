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
            'name' => $this->name,
            'objective' => $this->objective,
            'type' => $this->type->value,
            'benefit_category' => $this->benefit_category,
            'eligibility' => $this->eligibility ?? [],
            'enforce_eligibility' => $this->enforce_eligibility,
            'status' => $this->status->value,
            // Archive provenance (§10). `is_archived` reads off the authoritative
            // timestamp, not the status enum, so the two can never disagree here.
            'is_archived' => $this->isArchived(),
            'archived_at' => $this->archived_at?->toIso8601String(),
            'archive_reason' => $this->archive_reason,
            'activities_count' => $this->whenCounted('activities'),
            // Catalog USAGE: distinct MDAs running an activity for this programme (§10 —
            // one global programme, many MDAs, each through its own activity).
            'mdas_count' => $this->whenCounted('mdas'),
            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
