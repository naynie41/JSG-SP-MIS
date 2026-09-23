<?php

declare(strict_types=1);

namespace App\Http\Resources\Library;

use App\Domain\Library\Models\LibraryItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * What the administration console is told about a resource: everything the public
 * shape carries, plus the editorial state it needs to manage it.
 *
 * The counterpart is {@see PublicLibraryItemResource}, which is a separate class
 * rather than this one with fields hidden — see the note there for why.
 *
 * `stored_path` and `checksum_sha256` are still absent. Neither is useful in the
 * console, and a storage path in a JSON response is an invitation to build a direct
 * link to it, which would defeat the point of keeping the disk private.
 *
 * @mixin LibraryItem
 */
class LibraryItemResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'category_label' => $this->categoryLabel(),
            'featured' => $this->featured,
            'kind' => $this->kind->value,

            'original_filename' => $this->original_filename,
            'size_bytes' => $this->size_bytes,
            'mime_type' => $this->mime_type,
            'external_url' => $this->external_url,
            'has_thumbnail' => $this->thumbnail_path !== null,
            'thumbnail_url' => $this->thumbnail_path === null
                ? null
                : route('public.library.thumbnail', ['libraryItem' => $this->id]),

            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'published_at' => $this->published_at?->toIso8601String(),
            'download_count' => $this->download_count,

            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
