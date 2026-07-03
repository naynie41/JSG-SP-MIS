<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Registry\Models\ServeRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ServeRequest
 */
class ServeRequestResource extends JsonResource
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
            'status' => $this->status->value,
            'reason' => $this->reason,
            'decided_at' => $this->decided_at?->toIso8601String(),
            'decision_reason' => $this->decision_reason,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
