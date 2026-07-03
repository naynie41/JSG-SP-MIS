<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Matching\Engine\MatchResult;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * One ranked match from an ad-hoc serve search (PRD FR-DUP-04): the outcome band,
 * its score and the fields that drove it, plus the matched beneficiary projected
 * through the reveal seam (never the full profile).
 *
 * Wraps ['result' => MatchResult, 'beneficiary' => Beneficiary].
 */
class MatchCandidateResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var MatchResult $result */
        $result = $this->resource['result'];
        /** @var Beneficiary $beneficiary */
        $beneficiary = $this->resource['beneficiary'];

        return [
            'band' => $result->band->value,
            'score' => round($result->score->composite, 4),
            'matched_fields' => $result->score->matchedFields(),
            'beneficiary' => (new BeneficiaryRevealResource($beneficiary))->resolve(),
        ];
    }
}
