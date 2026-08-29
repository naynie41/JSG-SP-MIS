<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Graduation\Models\GraduationEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A recorded graduation (FR-GRD-02). Carries no beneficiary PII — only references —
 * so it is safe for the graduation history and notifications.
 *
 * @mixin GraduationEvent
 */
class GraduationEventResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'enrollment_id' => $this->enrollment_id,
            'beneficiary_id' => $this->beneficiary_id,
            'household_id' => $this->household_id,
            'programme_id' => $this->programme_id,
            'activity_id' => $this->activity_id,
            'mda_id' => $this->mda_id,
            'criteria_id' => $this->criteria_id,
            'reason' => $this->reason,
            'decided_by' => $this->decided_by,
            /*
             * Names, so the record reads as a record.
             *
             * A graduation is a judgement about someone's circumstances; a list of UUIDs
             * cannot be reviewed by the people accountable for those judgements. Only
             * loaded relations are exposed, so a caller that did not ask for them pays
             * nothing — and the history is MDA-scoped on `mda_id`, so the only names here
             * belong to people this MDA graduated.
             */
            'decided_by_name' => $this->whenLoaded('decidedBy', fn () => $this->decidedBy?->name),
            'subject' => [
                'type' => $this->household_id !== null ? 'household' : 'beneficiary',
                // A household has no name of its own, so it is identified the way people
                // refer to one: by its head, falling back to the source reference it
                // arrived under when no head has been designated.
                'name' => $this->household_id !== null
                    ? $this->whenLoaded('household', fn () => $this->household?->head?->fullName()
                        ?? $this->household?->original_record_id)
                    : $this->whenLoaded('beneficiary', fn () => $this->beneficiary?->fullName()),
            ],
            'criteria_name' => $this->whenLoaded('criteria', fn () => $this->criteria?->name),
            'graduated_at' => $this->graduated_at?->toIso8601String(),
        ];
    }
}
