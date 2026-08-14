<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Sync\Models\SyncConnector;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin SyncConnector
 */
class SyncConnectorResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'source' => $this->source->value,
            'owner_mda_id' => $this->owner_mda_id,
            'owner_mda' => $this->whenLoaded('ownerMda', fn () => ['id' => $this->ownerMda->id, 'name' => $this->ownerMda->name]),
            'conflict_policy' => $this->conflict_policy->value,
            'enabled' => $this->enabled,
            'schedule' => $this->schedule,
            'last_run_at' => $this->last_run_at?->toIso8601String(),
            /*
             * The standing mapping approval (CLAUDE.md §11), as an administrator needs
             * to see it. `stale` is distinct from `never_configured` because the remedy
             * differs: one needs a first mapping, the other needs a REVIEW of one that
             * used to be right — and only the second means a feed has stopped.
             */
            'mapping' => [
                'status' => $this->mappingStatus(),
                'confirmed_at' => $this->mapping_confirmed_at?->toIso8601String(),
                'confirmed_by' => $this->whenLoaded('mappingConfirmedBy', fn () => $this->mappingConfirmedBy?->name),
                'stale_at' => $this->mapping_stale_at?->toIso8601String(),
                'stale_reason' => $this->mapping_stale_reason,
                'can_enable' => $this->mappingIsConfirmed() && ! $this->mappingIsStale(),
            ],
        ];
    }
}
