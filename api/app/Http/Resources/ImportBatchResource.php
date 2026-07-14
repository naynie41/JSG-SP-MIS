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
        $draft = is_array($this->draft_activity) ? $this->draft_activity : null;
        $target = $draft['target_beneficiaries'] ?? null;

        return [
            'id' => $this->id,
            'owner_mda_id' => $this->owner_mda_id,
            'uploaded_by' => $this->uploaded_by,
            'original_filename' => $this->original_filename,
            'source' => $this->source->value,
            'activity_id' => $this->activity_id,
            // Activity-wizard preview (§10): unbound batch whose activity is created on
            // confirm. Non-null name + null activity_id ⇒ confirm via the wizard endpoint.
            'draft_activity_name' => $draft['name'] ?? null,
            // The wizard's target beneficiaries + a NON-BLOCKING mismatch flag: a warning
            // is shown when the uploaded row count differs from the target, but it never
            // blocks the commit (§10).
            'draft_target_beneficiaries' => $target === null ? null : (int) $target,
            'target_mismatch' => $target !== null && $this->total_rows !== null && (int) $this->total_rows !== (int) $target,
            'status' => $this->status->value,
            'summary' => [
                'total_rows' => $this->total_rows,
                'valid_rows' => $this->valid_rows,
                'invalid_rows' => $this->invalid_rows,
                // Two validation groups shown distinctly (PRD §9, FR-REG-05/09):
                // rows rejected on a malformed identity field vs rows kept with a
                // non-identity field dropped/flagged.
                'rejected_rows' => $this->rejected_rows,
                'dropped_field_rows' => $this->dropped_field_rows,
                'committed_rows' => $this->committed_rows,
                'served_rows' => $this->served_rows,
                'skipped_rows' => $this->skipped_rows,
            ],
            'error' => $this->error,
            'rows' => ImportRowResource::collection($this->whenLoaded('rows')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
