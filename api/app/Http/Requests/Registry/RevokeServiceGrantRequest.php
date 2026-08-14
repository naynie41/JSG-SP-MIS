<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Withdraw a cross-MDA read grant (PRD FR-OWN-07).
 *
 * The reason is OPTIONAL here, unlike declining a Service Request where it is required.
 * Declining refuses a colleague's request and owes them an explanation; revoking is
 * often the routine end of a service episode, and forcing a sentence would only
 * produce filler. Whatever is given is stored on the grant and audited.
 */
class RevokeServiceGrantRequest extends FormRequest
{
    public function authorize(): bool
    {
        // The grant-level check is the policy (OwnerMdaPolicy::revoke) in the controller.
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'reason' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
