<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Registry\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ServiceRequest
 */
class ServiceRequestResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'beneficiary_id' => $this->beneficiary_id,
            // Reveal-safe name for display (never NIN/BVN/contact); loaded on the list.
            'beneficiary_name' => $this->whenLoaded('beneficiary', fn () => $this->beneficiary->fullName()),
            'from_mda_id' => $this->from_mda_id,
            'to_mda_id' => $this->to_mda_id,
            'owner_mda' => $this->whenLoaded('toMda', fn () => ['id' => $this->toMda->id, 'name' => $this->toMda->name]),
            'activity_id' => $this->activity_id,
            'status' => $this->status->value,
            'reason' => $this->reason,
            'decided_at' => $this->decided_at?->toIso8601String(),
            'decision_reason' => $this->decision_reason,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
