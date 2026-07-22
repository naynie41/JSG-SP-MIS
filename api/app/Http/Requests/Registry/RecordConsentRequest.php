<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Registry\Enums\ConsentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Record (grant or withdraw) a beneficiary's consent for a PURPOSE (NFR-PRV-01).
 * Only the owner MDA may — enforced by the controller policy. `unknown` is not
 * settable via the API; you explicitly grant or withdraw. The purpose defaults to
 * cross-MDA sharing; any other purpose must be one registered in config/privacy.php.
 */
class RecordConsentRequest extends FormRequest
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
            'status' => ['required', Rule::in([ConsentStatus::Granted->value, ConsentStatus::Withdrawn->value])],
            'purpose' => ['nullable', 'string', Rule::in(array_keys((array) config('privacy.consent.purposes', [])))],
            'basis' => ['nullable', 'string', 'max:1000'],
            'source' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
