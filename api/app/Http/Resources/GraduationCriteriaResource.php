<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Graduation\Enums\GraduationCriterionType;
use App\Domain\Graduation\Models\GraduationCriteria;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * An MDA's graduation criteria set for a programme (FR-GRD-01). Each rule is labelled
 * so the config UI can render it without hard-coding the dimension names.
 *
 * @mixin GraduationCriteria
 */
class GraduationCriteriaResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'programme_id' => $this->programme_id,
            'owner_mda_id' => $this->owner_mda_id,
            'name' => $this->name,
            'logic' => $this->logic->value,
            'is_active' => $this->is_active,
            'rules' => collect($this->rules)->map(function (array $rule): array {
                $type = GraduationCriterionType::tryFrom((string) ($rule['type'] ?? ''));

                return [
                    'type' => $rule['type'] ?? null,
                    'label' => $type?->label(),
                    'threshold' => isset($rule['threshold']) ? (float) $rule['threshold'] : null,
                    'automatic' => $type?->isAutomatic() ?? false,
                ];
            })->all(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
