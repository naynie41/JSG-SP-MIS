<?php

declare(strict_types=1);

namespace App\Http\Requests\Referral;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Body for a referral transition (PRD FR-REF-02). `reason` is required only when
 * rejecting; other transitions carry an optional note/outcome.
 */
class TransitionReferralRequest extends FormRequest
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
        $isReject = $this->routeIs('referrals.reject');

        return [
            'reason' => [$isReject ? 'required' : 'nullable', 'string', 'max:1000'],
            'note' => ['nullable', 'string', 'max:1000'],
            'outcome' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
