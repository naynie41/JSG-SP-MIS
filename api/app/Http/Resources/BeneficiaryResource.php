<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Full beneficiary view for the owner MDA. NIN/BVN are masked by default (a
 * permission-gated reveal is added with the registry screens); JSON keys are
 * snake_case per CONVENTIONS.md.
 *
 * @mixin Beneficiary
 */
class BeneficiaryResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner_mda_id' => $this->owner_mda_id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'full_name' => $this->fullName(),
            'nin' => self::maskTail($this->nin),
            'bvn' => self::maskTail($this->bvn),
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth?->toDateString(),
            'gender' => $this->gender?->value,
            'address' => $this->address,
            'lga' => $this->lga,
            'ward' => $this->ward,
            'registration_source' => $this->registration_source->value,
            'registration_date' => $this->registration_date->toDateString(),
            'status' => $this->status->value,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }

    /** Show only the last 4 digits of an identifier. */
    private static function maskTail(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return str_repeat('•', max(0, mb_strlen($value) - 4)).mb_substr($value, -4);
    }
}
