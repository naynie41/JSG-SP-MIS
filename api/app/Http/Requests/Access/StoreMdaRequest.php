<?php

declare(strict_types=1);

namespace App\Http\Requests\Access;

use App\Domain\Access\Enums\MdaStatus;
use App\Domain\Access\Enums\MdaType;
use App\Domain\Programme\Rules\IsFundingPartner;
use App\Http\Requests\Access\Concerns\LinksFunderAccount;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMdaRequest extends FormRequest
{
    use LinksFunderAccount;

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
            'name' => ['required', 'string', 'max:255', Rule::unique('mdas', 'name')],
            'type' => ['required', Rule::enum(MdaType::class)],
            // Only a partner organisation has a funder account to link; see
            // LinksFunderAccount for the pairing rule.
            'funder_user_id' => ['nullable', 'uuid', new IsFundingPartner],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'status' => ['sometimes', Rule::enum(MdaStatus::class)],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(fn (Validator $v) => $this->validateFunderAccount($v));
    }
}
