<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Designate/replace a household's head (must be a current member).
 */
class DesignateHeadRequest extends FormRequest
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
        ];
    }
}
