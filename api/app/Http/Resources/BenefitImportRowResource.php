<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Benefit\Models\BenefitImportRow;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin BenefitImportRow
 */
class BenefitImportRowResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'row_number' => $this->row_number,
            'is_valid' => $this->is_valid,
            'errors' => $this->errors ?? [],
            'eligibility_flagged' => $this->eligibility_flagged,
            'resolved_beneficiary_id' => $this->resolved_beneficiary_id,
            'benefit_id' => $this->benefit_id,
            'delivery' => $this->payload, // parsed benefit fields (no PII)
        ];
    }
}
