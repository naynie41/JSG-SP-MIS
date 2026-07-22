<?php

declare(strict_types=1);

namespace App\Http\Requests\Graduation;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Records a graduation for an enrolment (FR-GRD-02). A reason is optional but
 * recommended — it is captured on the permanent graduation record and audited.
 */
class GraduateRequest extends FormRequest
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
