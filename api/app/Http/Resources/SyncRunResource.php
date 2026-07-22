<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Sync\Models\SyncRun;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A sync run with its outcome tallies (FR-DSH-02). Row-level outcomes are included as
 * `rows` when the relation is loaded (the run log).
 *
 * @mixin SyncRun
 */
class SyncRunResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'connector_id' => $this->connector_id,
            'trigger' => $this->trigger->value,
            'source' => $this->source,
            'owner_mda_id' => $this->owner_mda_id,
            'conflict_policy' => $this->conflict_policy->value,
            'status' => $this->status->value,
            'summary' => [
                'fetched' => $this->fetched_count,
                'created' => $this->created_count,
                'updated' => $this->updated_count,
                'skipped' => $this->skipped_count,
                'flagged' => $this->flagged_count,
                'rejected' => $this->rejected_count,
                'errors' => $this->error_count,
            ],
            'error' => $this->error,
            'started_at' => $this->started_at?->toIso8601String(),
            'finished_at' => $this->finished_at?->toIso8601String(),
            'rows' => $this->whenLoaded('rows', fn () => $this->rows->map(fn ($row): array => [
                'original_record_id' => $row->original_record_id,
                'outcome' => $row->outcome->value,
                'beneficiary_id' => $row->beneficiary_id,
                'match_band' => $row->match_band,
                'detail' => $row->detail,
            ])),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
