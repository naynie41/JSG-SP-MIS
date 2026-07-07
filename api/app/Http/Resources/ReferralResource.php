<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Referral\Models\Referral;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Referral
 */
class ReferralResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'beneficiary_id' => $this->beneficiary_id,
            'from_mda_id' => $this->from_mda_id,
            'to_mda_id' => $this->to_mda_id,
            'need' => $this->need,
            'notes' => $this->notes,
            'status' => $this->status->value,
            'outcome' => $this->outcome,
            'reason' => $this->reason,
            'info_request' => $this->info_request,
            'info_response' => $this->info_response,
            'escalation_level' => $this->escalation_level,
            'sla_breached_at' => $this->sla_breached_at?->toIso8601String(),
            'timeline' => [
                'created_at' => $this->created_at?->toIso8601String(),
                'accepted_at' => $this->accepted_at?->toIso8601String(),
                'rejected_at' => $this->rejected_at?->toIso8601String(),
                'info_requested_at' => $this->info_requested_at?->toIso8601String(),
                'info_responded_at' => $this->info_responded_at?->toIso8601String(),
                'started_at' => $this->started_at?->toIso8601String(),
                'completed_at' => $this->completed_at?->toIso8601String(),
                'closed_at' => $this->closed_at?->toIso8601String(),
            ],
        ];
    }
}
