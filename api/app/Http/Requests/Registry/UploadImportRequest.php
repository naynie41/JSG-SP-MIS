<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Upload an Excel/CSV file for bulk beneficiary import (PRD FR-REG-02).
 */
class UploadImportRequest extends FormRequest
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
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls', 'max:10240'],
        ];
    }
}
