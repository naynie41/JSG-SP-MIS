<?php

declare(strict_types=1);

namespace App\Http\Requests\Referral;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Create a referral (PRD FR-REF-01): the originating MDA specifies the beneficiary,
 * the target MDA, the identified need, and optional notes.
 */
class StoreReferralRequest extends FormRequest
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
            'beneficiary_id' => ['required', 'uuid'],
            'to_mda_id' => ['required', 'uuid', 'exists:mdas,id'],
            'need' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
