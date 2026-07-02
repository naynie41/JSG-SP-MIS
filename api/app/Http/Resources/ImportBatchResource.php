<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Registry\Models\ImportBatch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A bulk import batch with its progress summary (PRD FR-REG-02/06). Staged rows
 * are included as `rows` when the relation is loaded (the preview).
 *
 * @mixin ImportBatch
 */
class ImportBatchResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner_mda_id' => $this->owner_mda_id,
            'uploaded_by' => $this->uploaded_by,
            'original_filename' => $this->original_filename,
            'source' => $this->source->value,
            'status' => $this->status->value,
            'summary' => [
                'total_rows' => $this->total_rows,
                'valid_rows' => $this->valid_rows,
                'invalid_rows' => $this->invalid_rows,
                'committed_rows' => $this->committed_rows,
            ],
            'error' => $this->error,
            'rows' => ImportRowResource::collection($this->whenLoaded('rows')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
