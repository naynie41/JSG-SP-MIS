<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Matching\Models\MatchingConfig;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MatchingConfig
 */
class MatchingConfigResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'version' => $this->version,
            'is_active' => $this->is_active,
            'deterministic_rules' => $this->deterministic_rules,
            'fuzzy_fields' => $this->fuzzy_fields,
            'review_threshold' => $this->review_threshold,
            'auto_accept_threshold' => $this->auto_accept_threshold,
            'exact_match_behaviour' => $this->exact_match_behaviour->value,
            'description' => $this->description,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
