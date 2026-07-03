<?php

declare(strict_types=1);

namespace App\Http\Requests\Registry;

use App\Domain\Registry\Enums\ImportRowResolution;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Resolve a flagged import row (PRD FR-DUP-05): create as new (with a required
 * justification), link/request-to-serve an existing beneficiary, or skip.
 */
class ResolveImportRowRequest extends FormRequest
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
            'resolution' => ['required', Rule::enum(ImportRowResolution::class)],
            // Creating a NEW record despite a potential duplicate needs a reason.
            'note' => ['required_if:resolution,new', 'nullable', 'string', 'max:1000'],
            // Linking/serving must target one of the row's matched existing records.
            'beneficiary_id' => ['required_if:resolution,link', 'nullable', 'uuid'],
        ];
    }
}
