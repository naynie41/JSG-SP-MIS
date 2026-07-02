<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Registry\Enums\DocumentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Upload a supporting document for a beneficiary (PRD FR-REG-07, SECURITY.md §5).
 * Validates type, size AND actual content (`mimetypes` reads the real MIME via
 * finfo, not just the extension), restricting uploads to PDFs and common image
 * scans up to 5 MB.
 */
class UploadDocumentRequest extends FormRequest
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
            'document_type' => ['required', Rule::enum(DocumentType::class)],
            'file' => [
                'required',
                'file',
                'max:5120', // 5 MB
                'mimes:pdf,jpg,jpeg,png',
                'mimetypes:application/pdf,image/jpeg,image/png',
            ],
        ];
    }
}
