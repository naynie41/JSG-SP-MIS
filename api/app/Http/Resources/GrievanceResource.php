<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Grievance\Models\Grievance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Grievance
 */
class GrievanceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'handling_mda_id' => $this->handling_mda_id,
            'beneficiary_id' => $this->beneficiary_id,
            'category' => $this->category->value,
            'channel' => $this->channel->value,
            'description' => $this->description,
            'status' => $this->status->value,
            'assignee_user_id' => $this->assignee_user_id,
            'resolution_notes' => $this->resolution_notes,
            'submitted_by' => $this->submitted_by,
            'escalation_level' => $this->escalation_level,
            'sla_breached_at' => $this->sla_breached_at?->toIso8601String(),
            'timeline' => [
                'created_at' => $this->created_at?->toIso8601String(),
                'assigned_at' => $this->assigned_at?->toIso8601String(),
                'started_at' => $this->started_at?->toIso8601String(),
                'resolved_at' => $this->resolved_at?->toIso8601String(),
                'closed_at' => $this->closed_at?->toIso8601String(),
            ],
        ];
    }
}
