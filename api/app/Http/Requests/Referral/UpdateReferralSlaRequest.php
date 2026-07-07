<?php

declare(strict_types=1);

namespace App\Http\Requests\Referral;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Set the SLA window (hours) for a referral status (PRD FR-REF-04/05).
 */
class UpdateReferralSlaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'hours' => ['required', 'integer', 'min:1', 'max:8760'],
        ];
    }
}
