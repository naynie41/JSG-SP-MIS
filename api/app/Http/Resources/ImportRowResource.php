<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Registry\Models\ImportRow;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * A staged import row in the preview (PRD FR-REG-06): its validation result and a
 * minimal, identifier-masked snapshot for review. Full NIN/BVN are never echoed.
 *
 * @mixin ImportRow
 */
class ImportRowResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $payload = $this->payload;

        return [
            'row_number' => $this->row_number,
            'original_record_id' => $this->original_record_id,
            'is_valid' => $this->is_valid,
            'errors' => $this->errors ?? [],
            'beneficiary_id' => $this->beneficiary_id,
            'resolution' => $this->resolution,
            'resolution_note' => $this->resolution_note,
            'resolved_beneficiary_id' => $this->resolved_beneficiary_id,
            // Duplicate outcome (FR-DUP-01/04). The controller attaches the resolved
            // reveal payloads as `match_view`; fall back to the stored band.
            'match' => $this->resource->getAttribute('match_view') ?? [
                'band' => $this->match_band ?? 'none',
                'candidates' => [],
            ],
            'preview' => [
                'first_name' => $payload['first_name'] ?? null,
                'last_name' => $payload['last_name'] ?? null,
                'nin' => self::maskTail($payload['nin'] ?? null),
                'bvn' => self::maskTail($payload['bvn'] ?? null),
                'phone' => $payload['phone'] ?? null,
                'date_of_birth' => $payload['date_of_birth'] ?? null,
                'gender' => $payload['gender'] ?? null,
                'lga' => $payload['lga'] ?? null,
                'ward' => $payload['ward'] ?? null,
            ],
        ];
    }

    private static function maskTail(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return str_repeat('•', max(0, mb_strlen($value) - 4)).mb_substr($value, -4);
    }
}
