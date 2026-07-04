<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Programme\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Enrollment
 */
class EnrollmentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'programme_id' => $this->programme_id,
            'activity_id' => $this->activity_id,
            'mda_id' => $this->mda_id, // the enrolling MDA
            'beneficiary_id' => $this->beneficiary_id,
            'household_id' => $this->household_id,
            'status' => $this->status->value,
            'enrolled_on' => $this->enrolled_on->toDateString(),
            'exited_on' => $this->exited_on?->toDateString(),
            'exit_reason' => $this->exit_reason,
            'eligibility_flagged' => $this->eligibility_flagged,
            'eligibility_notes' => $this->eligibility_notes,
            'enrolled_by' => $this->enrolled_by,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
