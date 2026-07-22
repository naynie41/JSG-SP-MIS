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
            'graduated_at' => $this->graduated_at?->toIso8601String(),
        ];
    }
}
