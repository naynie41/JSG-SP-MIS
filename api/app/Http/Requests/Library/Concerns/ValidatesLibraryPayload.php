<?php

declare(strict_types=1);

namespace App\Http\Requests\Library\Concerns;

use App\Domain\Library\Enums\LibraryItemKind;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

/**
 * The rules shared by creating and updating a library resource.
 *
 * Two of them carry real weight:
 *
 *  - **A resource is a file OR a link, never both and never neither.** The public
 *    card renders one button, and the download endpoint dereferences `stored_path`
 *    without an authenticated user in front of it. Enforced here, again by the model,
 *    and again by a Postgres CHECK.
 *  - **Extension AND MIME must both be in the allowlist.** A browser-supplied MIME
 *    can be spoofed and an extension can mislead, so neither alone is a control.
 *    Note what the list omits — html, svg, xml — because the download endpoint is
 *    unauthenticated and serving a document that renders from the State's own origin
 *    is a stored-XSS primitive regardless of headers.
 */
trait ValidatesLibraryPayload
{
    /**
     * @return array<string, mixed>
     */
    protected function libraryMetadataRules(bool $required): array
    {
        $req = $required ? 'required' : 'sometimes';

        return [
            'title' => [$req, 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category' => [$req, 'string', Rule::in(array_keys((array) config('library.categories', [])))],
            'featured' => ['sometimes', 'boolean'],
            'kind' => [$req, Rule::enum(LibraryItemKind::class)],
            'external_url' => ['nullable', 'url', 'max:2048', 'starts_with:https://,http://'],
            'file' => $this->fileRules($required),
            'thumbnail' => $this->thumbnailRules(),
        ];
    }

    /**
     * @return array<int, mixed>
     */
    protected function fileRules(bool $requiredForCreate): array
    {
        // `required_if` rather than `required`: a link resource legitimately has no
        // file, and on UPDATE a file resource keeps the one it already has unless a
        // replacement is sent.
        $rules = $requiredForCreate
            ? ['required_if:kind,'.LibraryItemKind::File->value]
            : ['sometimes'];

        return array_merge($rules, [
            'nullable',
            'file',
            'max:'.(int) config('library.max_file_kb'),
            'mimes:'.implode(',', (array) config('library.allowed_extensions')),
            'mimetypes:'.implode(',', (array) config('library.allowed_mimes')),
        ]);
    }

    /**
     * @return array<int, mixed>
     */
    protected function thumbnailRules(): array
    {
        return [
            'sometimes',
            'nullable',
            'file',
            'max:'.(int) config('library.max_thumbnail_kb'),
            'mimes:'.implode(',', (array) config('library.allowed_thumbnail_extensions')),
            'mimetypes:'.implode(',', (array) config('library.allowed_thumbnail_mimes')),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function libraryMessages(): array
    {
        return [
            'file.mimes' => 'That file type is not accepted. Use a PDF, Word, Excel, PowerPoint, CSV or text document.',
            'file.mimetypes' => 'That file type is not accepted. Use a PDF, Word, Excel, PowerPoint, CSV or text document.',
            'file.max' => 'The file is too large. The limit is '.round(((int) config('library.max_file_kb')) / 1024).' MB.',
            'thumbnail.mimes' => 'The thumbnail must be a JPG, PNG or WebP image.',
            'thumbnail.mimetypes' => 'The thumbnail must be a JPG, PNG or WebP image.',
            'external_url.starts_with' => 'Enter a full web address beginning with https://',
        ];
    }

    /**
     * The exclusivity rule. `$hasStoredFile` lets an UPDATE count the file already on
     * the record, so editing a file resource's title does not demand the file again.
     */
    protected function validateKindShape(Validator $validator, bool $hasStoredFile = false): void
    {
        $kind = $this->input('kind');
        if ($kind === null) {
            return;
        }

        $url = trim((string) $this->input('external_url', ''));
        $sendingFile = $this->hasFile('file');

        if ($kind === LibraryItemKind::File->value) {
            if (! $sendingFile && ! $hasStoredFile) {
                $validator->errors()->add('file', 'Upload a file, or change this resource to an external link.');
            }
            if ($url !== '') {
                $validator->errors()->add('external_url', 'A resource is a file or a link, not both. Clear the web address to keep the file.');
            }

            return;
        }

        if ($kind === LibraryItemKind::Link->value) {
            if ($url === '') {
                $validator->errors()->add('external_url', 'Enter the web address, or change this resource to an uploaded file.');
            }
            if ($sendingFile) {
                $validator->errors()->add('file', 'A resource is a file or a link, not both. Remove the file to keep the web address.');
            }
        }
    }
}
