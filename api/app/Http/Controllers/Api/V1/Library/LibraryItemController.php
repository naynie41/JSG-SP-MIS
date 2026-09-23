<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Library;

use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Library\Enums\LibraryItemKind;
use App\Domain\Library\Enums\LibraryItemStatus;
use App\Domain\Library\Models\LibraryItem;
use App\Domain\Library\Services\LibraryFileStore;
use App\Http\Controllers\Controller;
use App\Http\Requests\Library\StoreLibraryItemRequest;
use App\Http\Requests\Library\UpdateLibraryItemRequest;
use App\Http\Resources\Library\LibraryItemResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Administration of the public resource library (FR-RES-02/03), System
 * Administrator only via `permission:library.*` on the routes.
 *
 * The public counterpart is {@see PublicLibraryController}. Keeping them apart
 * matters more here than usual: one of them answers to an authenticated
 * administrator and the other to the open internet, and a shared controller is how
 * a draft eventually ships with a published one.
 */
class LibraryItemController extends Controller
{
    public function __construct(
        private readonly LibraryFileStore $files,
        private readonly AuditLogger $audit,
    ) {}

    /** Every resource, in every state — this is the editorial list, not the public one. */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', LibraryItem::class);

        $query = LibraryItem::query();

        if (($status = trim((string) $request->query('status', ''))) !== '') {
            $query->where('status', $status);
        }
        if (($category = trim((string) $request->query('category', ''))) !== '') {
            $query->where('category', $category);
        }

        $items = $query->orderByDesc('created_at')->get();

        return ApiResponse::success([
            'items' => LibraryItemResource::collection($items)->resolve(),
            'categories' => (array) config('library.categories', []),
        ]);
    }

    public function store(StoreLibraryItemRequest $request): JsonResponse
    {
        $this->authorize('create', LibraryItem::class);

        $kind = LibraryItemKind::from((string) $request->string('kind')->value());
        $status = LibraryItemStatus::from((string) ($request->input('status') ?? LibraryItemStatus::Draft->value));

        $attributes = [
            'title' => $request->string('title')->value(),
            'description' => $request->input('description'),
            'category' => $request->string('category')->value(),
            'featured' => $request->boolean('featured'),
            'kind' => $kind->value,
            'status' => $status->value,
            // Stamped from the status rather than accepted from the client: a
            // published_at that disagrees with status would reorder the public page.
            'published_at' => $status->isPublic() ? now() : null,
            'created_by' => $request->user()?->id,
        ];

        $attributes += $kind === LibraryItemKind::File
            ? $this->files->putFile($request->file('file'))
            : ['external_url' => $request->string('external_url')->value()];

        if ($request->hasFile('thumbnail')) {
            $attributes += $this->files->putThumbnail($request->file('thumbnail'));
        }

        $item = LibraryItem::create($attributes);

        $this->audit->record('library_item.created', $item);

        return ApiResponse::success(
            ['resource' => (new LibraryItemResource($item))->resolve()],
            status: 201,
        );
    }

    public function update(UpdateLibraryItemRequest $request, LibraryItem $libraryItem): JsonResponse
    {
        $this->authorize('update', $libraryItem);

        // Captured before anything changes: replacing a file or switching a file
        // resource to a link leaves the old upload orphaned on disk, and the only
        // moment we still know its path is now.
        $previousFile = $libraryItem->stored_path;
        $previousThumbnail = $libraryItem->thumbnail_path;

        $attributes = $request->safe()->only(['title', 'description', 'category', 'featured']);

        if ($request->has('kind')) {
            $kind = LibraryItemKind::from((string) $request->string('kind')->value());
            $attributes['kind'] = $kind->value;

            if ($kind === LibraryItemKind::Link) {
                // Switching to a link clears the file columns, or the CHECK
                // constraint rejects the row and the request 500s.
                $attributes += [
                    'external_url' => $request->string('external_url')->value(),
                    'stored_path' => null,
                    'original_filename' => null,
                    'mime_type' => null,
                    'size_bytes' => null,
                    'checksum_sha256' => null,
                ];
            } else {
                $attributes['external_url'] = null;
            }
        }

        if ($request->hasFile('file')) {
            $attributes += $this->files->putFile($request->file('file'));
            $attributes['external_url'] = null;
        }

        if ($request->hasFile('thumbnail')) {
            $attributes += $this->files->putThumbnail($request->file('thumbnail'));
        }

        if ($request->has('status')) {
            $status = LibraryItemStatus::from((string) $request->string('status')->value());
            $attributes['status'] = $status->value;
            // First publication stamps the date; re-publishing something archived
            // keeps the original, so the public ordering does not jump.
            if ($status->isPublic() && $libraryItem->published_at === null) {
                $attributes['published_at'] = now();
            }
        }

        DB::transaction(function () use ($libraryItem, $attributes): void {
            $libraryItem->update($attributes);
        });

        // Only after the row is safely written. Deleting first would lose the file
        // if the update then failed.
        if (($attributes['stored_path'] ?? $previousFile) !== $previousFile) {
            $this->files->forget($previousFile);
        }
        if (($attributes['thumbnail_path'] ?? $previousThumbnail) !== $previousThumbnail) {
            $this->files->forget($previousThumbnail);
        }

        $this->audit->record('library_item.updated', $libraryItem);

        return ApiResponse::success(
            ['resource' => (new LibraryItemResource($libraryItem->fresh()))->resolve()],
        );
    }

    /**
     * Soft-delete — for something added in error.
     *
     * Withdrawing a published resource is an ARCHIVE (a status update), not this:
     * archiving keeps the download history, which CLAUDE.md §10 requires of any
     * record carrying history. The stored file is deliberately left on disk so the
     * delete stays reversible.
     */
    public function destroy(LibraryItem $libraryItem): JsonResponse
    {
        $this->authorize('delete', $libraryItem);

        $libraryItem->delete();

        $this->audit->record('library_item.deleted', $libraryItem);

        return ApiResponse::success(['deleted' => true]);
    }
}
