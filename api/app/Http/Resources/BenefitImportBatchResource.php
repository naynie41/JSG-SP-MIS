<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Benefit\Models\BenefitImportBatch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin BenefitImportBatch
 */
class BenefitImportBatchResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mda_id' => $this->mda_id, // importing = delivering MDA
            'activity_id' => $this->activity_id,
            'programme_id' => $this->programme_id,
            'original_filename' => $this->original_filename,
            'source' => $this->source,
            'status' => $this->status->value,
            'summary' => [
                'total_rows' => $this->total_rows,
                'valid_rows' => $this->valid_rows,
                'invalid_rows' => $this->invalid_rows,
                'committed_rows' => $this->committed_rows,
            ],
            'error' => $this->error,
            'rows' => BenefitImportRowResource::collection($this->whenLoaded('rows')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
