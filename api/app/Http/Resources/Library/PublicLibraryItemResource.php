<?php

declare(strict_types=1);

namespace App\Http\Resources\Library;

use App\Domain\Library\Models\LibraryItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * What an anonymous visitor is told about a resource.
 *
 * A SEPARATE class from the admin one, deliberately. Sharing a resource class and
 * hiding fields conditionally is how internal data reaches an unauthenticated
 * response: the condition is one edit away from being wrong, and nothing fails
 * loudly when it is. Here the public shape is the whole class, so anything absent
 * is absent by construction.
 *
 * Never included: who created it, its draft history, the stored path, the checksum,
 * or the raw status. A visitor sees a published resource or sees nothing.
 *
 * @mixin LibraryItem
 */
class PublicLibraryItemResource extends JsonResource
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

            // For a file: what to show on the card, and where the button goes. The
            // URL is a route, not a storage path — the file is private and there is
            // no direct link to leak.
            'original_filename' => $this->when($this->isFile(), $this->original_filename),
            'size_bytes' => $this->when($this->isFile(), $this->size_bytes),
            'mime_type' => $this->when($this->isFile(), $this->mime_type),
            'download_url' => $this->when(
                $this->isFile(),
                fn (): string => route('public.library.download', ['libraryItem' => $this->id]),
            ),

            // For a link: where it points.
            'external_url' => $this->when(! $this->isFile(), $this->external_url),

            'thumbnail_url' => $this->thumbnail_path === null
                ? null
                : route('public.library.thumbnail', ['libraryItem' => $this->id]),

            'published_at' => $this->published_at?->toIso8601String(),
            'download_count' => $this->download_count,
        ];
    }
}
