<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use Illuminate\Foundation\Http\FormRequest;

class BeneficiaryLookupRequest extends FormRequest
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
        // At least one identifier is required to look up.
        return [
            'nin' => ['required_without_all:bvn,phone', 'nullable', 'string', 'max:20'],
            'bvn' => ['required_without_all:nin,phone', 'nullable', 'string', 'max:20'],
            'phone' => ['required_without_all:nin,bvn', 'nullable', 'string', 'max:20'],
        ];
    }
}
