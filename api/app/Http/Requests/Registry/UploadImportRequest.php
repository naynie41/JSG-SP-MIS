<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Registry\Imports\Adapters\SourceAdapterRegistry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Upload a file for bulk beneficiary import (PRD FR-REG-02). Accepts plain
 * Excel/CSV as well as Kobo/ODK exports; the optional `source` selects the
 * ingestion adapter (and thus the stamped provenance). When omitted, the source
 * is inferred from the file extension (excel/csv).
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
        $sources = app(SourceAdapterRegistry::class)->importableSources();

        return [
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls', 'max:10240'],
            'source' => ['nullable', Rule::in($sources)],
        ];
    }
}
