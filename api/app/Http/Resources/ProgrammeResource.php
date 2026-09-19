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
            // Ownership (§10, revised). NULL is the central catalog every MDA reads;
            // a named MDA is a programme only that MDA (and oversight) can see.
            'owner_mda' => $this->whenLoaded('ownerMda', fn () => [
                'id' => $this->ownerMda?->id,
                'name' => $this->ownerMda?->name,
            ]),
            'owner_mda_id' => $this->owner_mda_id,
            'is_central' => $this->isCentral(),
            // The decision, kept apart from the delivery lifecycle above.
            'approval_status' => $this->approval_status->value,
            'approval_label' => $this->approval_status->label(),
            'submitted_at' => $this->submitted_at?->toIso8601String(),
            'approved_at' => $this->approved_at?->toIso8601String(),
            'decision_note' => $this->decision_note,
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
