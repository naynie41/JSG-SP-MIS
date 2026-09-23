<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Library;

use App\Domain\Library\Models\LibraryItem;
use App\Http\Controllers\Controller;
use App\Http\Resources\Library\PublicLibraryItemResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * The public face of the resource library (FR-RES-04) — the only controller in this
 * application that answers to nobody.
 *
 * Everything unusual about it follows from that:
 *
 *  - **Every query starts at `LibraryItem::published()`.** Not a `where` written here
 *    — the scope, every time. It is the single boundary between a draft and the open
 *    internet, and `PublicLibraryVisibilityTest` pins it.
 *  - **No `$request->user()` anywhere.** There is never one, and code that reads it
 *    would silently behave differently for a signed-in visitor.
 *  - **Files are streamed, never linked.** The disk is private, so withdrawal is
 *    real: archiving an item stops the download at once, where a public-disk URL
 *    would stay fetchable by anyone who kept it.
 *  - **Responses are attachments with `nosniff`.** Even with the extension/MIME
 *    allowlist, nothing served from the State's own origin should be able to render
 *    itself as a document.
 *
 * Rate limiting is applied at the route (`throttle:library`), not here.
 */
class PublicLibraryController extends Controller
{
    /** Published resources, newest first, featured pinned above the rest. */
    public function index(Request $request): JsonResponse
    {
        $query = LibraryItem::query()->published();

        if (($category = trim((string) $request->query('category', ''))) !== '') {
            $query->where('category', $category);
        }

        if (($search = trim((string) $request->query('search', ''))) !== '') {
            // Escaped so a visitor cannot turn the search box into a wildcard scan.
            $term = '%'.addcslashes($search, '%_\\').'%';
            $query->where(function ($q) use ($term): void {
                $q->where('title', 'like', $term)->orWhere('description', 'like', $term);
            });
        }

        $items = $query
            ->orderByDesc('featured')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get();

        return ApiResponse::success([
            'items' => PublicLibraryItemResource::collection($items)->resolve(),
            'categories' => $this->categories(),
        ]);
    }

    /** Stream a published file resource, and count the download. */
    public function download(LibraryItem $libraryItem): StreamedResponse|JsonResponse
    {
        if (! $libraryItem->isPublic() || ! $libraryItem->isFile() || $libraryItem->stored_path === null) {
            return $this->gone();
        }

        $disk = (string) config('library.disk', 'local');

        // A row pointing at a file that is not on disk is a deployment fault, not a
        // visitor error. 404 rather than letting Storage throw a 500 on a public page.
        if (! Storage::disk($disk)->exists($libraryItem->stored_path)) {
            report(new \RuntimeException("Library file missing from disk: {$libraryItem->stored_path}"));

            return $this->gone();
        }

        // Atomic, and deliberately not a model save: this runs unauthenticated and
        // concurrently, and `increment` leaves no room for a lost update. It also
        // sidesteps touching `updated_at`, which would make every download look like
        // an edit in the audit trail.
        DB::table('library_items')->where('id', $libraryItem->id)->increment('download_count');

        return Storage::disk($disk)->download(
            $libraryItem->stored_path,
            $libraryItem->original_filename ?? 'resource',
            [
                'Content-Type' => $libraryItem->mime_type ?? 'application/octet-stream',
                'X-Content-Type-Options' => 'nosniff',
            ],
        );
    }

    /** Serve a published resource's thumbnail image. */
    public function thumbnail(LibraryItem $libraryItem): StreamedResponse|JsonResponse
    {
        // The item's own visibility governs its thumbnail. Serving the image of a
        // draft would leak the existence, title and artwork of unpublished material.
        if (! $libraryItem->isPublic() || $libraryItem->thumbnail_path === null) {
            return $this->gone();
        }

        $disk = (string) config('library.disk', 'local');

        if (! Storage::disk($disk)->exists($libraryItem->thumbnail_path)) {
            return $this->gone();
        }

        // Inline, unlike the document download — this one is meant to render in an
        // <img>. Safe because the thumbnail allowlist is raster images only; nosniff
        // stops the browser second-guessing the declared type.
        return Storage::disk($disk)->response(
            $libraryItem->thumbnail_path,
            null,
            [
                'Content-Type' => $libraryItem->thumbnail_mime ?? 'image/jpeg',
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'public, max-age=86400',
            ],
        );
    }

    /**
     * One 404 for "not published", "not a file", "withdrawn" and "missing from disk".
     * A visitor gets no signal about which, so an archived resource cannot be probed
     * for and a draft's existence cannot be confirmed by its id.
     */
    private function gone(): JsonResponse
    {
        return ApiResponse::error('NOT_FOUND', 'That resource is not available.', [], 404);
    }

    /**
     * @return list<array{key: string, label: string}>
     */
    private function categories(): array
    {
        /** @var array<string, string> $configured */
        $configured = config('library.categories', []);

        // array_map over TWO arrays ignores their keys and returns a list already, so
        // there is nothing for array_values to do here.
        return array_map(
            static fn (string $key, string $label): array => ['key' => $key, 'label' => $label],
            array_keys($configured),
            $configured,
        );
    }
}
