<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Reporting\Models\ReportRun;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ReportRun
 */
class ReportRunResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'report_key' => $this->report_key,
            'report_label' => $this->report_label,
            'format' => $this->format,
            'status' => $this->status,
            'scope' => ['kind' => $this->scope_kind, 'label' => $this->scope_label],
            'row_count' => $this->row_count,
            'file_name' => $this->file_name,
            'error' => $this->error,
            'download_ready' => $this->isReady(),
            'created_at' => $this->created_at?->toIso8601String(),
            'completed_at' => $this->completed_at?->toIso8601String(),
        ];
    }
}
