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
        ];
    }
}
