<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use Illuminate\Foundation\Http\FormRequest;

class DecideOwnershipTransferRequest extends FormRequest
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
            'reason' => ['nullable', 'string', 'max:500'],
        ];
    }
}
