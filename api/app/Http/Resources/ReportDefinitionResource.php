<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Reporting\Models\ReportDefinition;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ReportDefinition
 */
class ReportDefinitionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'dataset' => $this->dataset,
            'definition' => [
                'group_by' => $this->definition['group_by'] ?? [],
                'measures' => $this->definition['measures'] ?? [],
                'filters' => $this->definition['filters'] ?? [],
            ],
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
