<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The minimal cross-MDA "reveal" projection (PRD FR-OWN-03 / FR-DUP-04): just
 * enough for another MDA to recognise an existing beneficiary and coordinate —
 * name + id, owner MDA, source, registration date, LGA/Ward, status, and the
 * programme(s) + benefits-received sections. Never exposes NIN/BVN/phone/address/DOB.
 *
 * The programme/benefit sections are present-but-empty until Phase 4 (enrolment +
 * benefit ledger); they populate from the loaded relations with no shape change.
 *
 * @mixin Beneficiary
 */
class BeneficiaryRevealResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->fullName(),
            'owner_mda' => $this->ownerMda ? ['id' => $this->ownerMda->id, 'name' => $this->ownerMda->name] : null,
            'registration_source' => $this->registration_source->value,
            'registration_date' => $this->registration_date->toDateString(),
            'lga' => $this->lga,
            'ward' => $this->ward,
            'status' => $this->status->value,
            // Phase 4 fills these from enrolments + the benefit ledger.
            'programmes' => [],
            'benefits' => ['summary' => null, 'items' => []],
        ];
    }
}
