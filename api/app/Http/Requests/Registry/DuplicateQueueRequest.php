<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Filters for the duplicate queue (FR-DUP-01/05).
 *
 * Band and state only. There is no MDA parameter by design: scope is the caller's, taken
 * from the global scope on the batch, never asked for.
 */
class DuplicateQueueRequest extends FormRequest
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
            'band' => ['sometimes', 'nullable', Rule::in(['exact', 'probable'])],
            'state' => ['sometimes', Rule::in(['awaiting', 'decided', 'all'])],
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
