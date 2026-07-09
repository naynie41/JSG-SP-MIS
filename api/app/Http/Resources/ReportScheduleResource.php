<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Reporting\Models\ReportSchedule;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ReportSchedule
 */
class ReportScheduleResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'report_key' => $this->report_key,
            'report_definition_id' => $this->report_definition_id,
            'format' => $this->format,
            'frequency' => $this->frequency,
            'delivery' => $this->delivery,
            'status' => $this->status,
            'scope' => ['kind' => $this->scope_kind, 'label' => $this->scope_label],
            'recipient_user_ids' => $this->recipient_user_ids,
            'last_run_on' => $this->last_run_on?->toDateString(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
