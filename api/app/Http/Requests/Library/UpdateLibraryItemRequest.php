<?php

declare(strict_types=1);

namespace App\Http\Requests\Library;

use App\Domain\Library\Enums\LibraryItemKind;
use App\Domain\Library\Enums\LibraryItemStatus;
use App\Domain\Library\Models\LibraryItem;
use App\Http\Requests\Library\Concerns\ValidatesLibraryPayload;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Edit a library resource, including publishing and archiving it (FR-RES-02/03).
 *
 * Unlike the create request, every field is optional — a PATCH that only flips
 * `status` is the normal way to publish or withdraw. The one rule that still has to
 * hold is the file-or-link shape, and it has to account for the file ALREADY on the
 * record: editing a title must not demand the file be re-uploaded.
 */
class UpdateLibraryItemRequest extends FormRequest
{
    use ValidatesLibraryPayload;

    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return array_merge($this->libraryMetadataRules(required: false), [
            // All three are reachable here: publishing a draft, and withdrawing a
            // published item to `archived`.
            'status' => ['sometimes', Rule::enum(LibraryItemStatus::class)],
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return $this->libraryMessages();
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            $item = $this->route('libraryItem');
            $existing = $item instanceof LibraryItem ? $item : null;

            // Only counts when the record is ALREADY a file resource. Switching a link
            // to a file must still require the upload, and an orphaned stored_path on
            // a link row cannot satisfy it.
            $hasStoredFile = $existing !== null
                && $existing->kind === LibraryItemKind::File
                && $existing->stored_path !== null;

            $this->validateKindShape($v, $hasStoredFile);
        });
    }
}
