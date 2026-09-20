<?php

declare(strict_types=1);

namespace App\Domain\Library\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Puts library uploads on disk and takes them off again.
 *
 * One class so that every rule about WHERE a library file lives is in one place.
 * The rules, and why:
 *
 *  - **The private disk, always.** `storage/app/public` is not a persisted volume
 *    in production — docker-compose.prod.yml mounts only `storage/app/private` —
 *    so a file written to the public disk vanishes on the next redeploy while its
 *    database row survives. The failure is silent and looks like data loss.
 *  - **A generated name, never the user's.** `store()` writes a random basename
 *    and keeps only the extension. Nothing user-supplied reaches the path, so
 *    there is no traversal and no way to plant an executable name.
 *  - **The original name is data, not a path.** It is stored on the row and used
 *    only as the download filename.
 *
 * Serving is the download controller's job, not this class's: the file is private,
 * so there is no URL to hand out and withdrawal is therefore real.
 */
class LibraryFileStore
{
    /**
     * @return array{stored_path: string, original_filename: string, mime_type: string, size_bytes: int, checksum_sha256: string}
     */
    public function putFile(UploadedFile $file): array
    {
        return [
            'stored_path' => $this->put($file, (string) config('library.file_directory')),
            'original_filename' => $file->getClientOriginalName(),
            // getMimeType() sniffs the file; getClientMimeType() is what the browser
            // claimed. Prefer the sniff, fall back only if it cannot tell.
            'mime_type' => $file->getMimeType() ?? $file->getClientMimeType(),
            'size_bytes' => (int) $file->getSize(),
            'checksum_sha256' => (string) hash_file('sha256', $file->getRealPath()),
        ];
    }

    /**
     * @return array{thumbnail_path: string, thumbnail_mime: string}
     */
    public function putThumbnail(UploadedFile $image): array
    {
        return [
            'thumbnail_path' => $this->put($image, (string) config('library.thumbnail_directory')),
            'thumbnail_mime' => $image->getMimeType() ?? $image->getClientMimeType(),
        ];
    }

    /**
     * Remove a stored file. Safe to call with null or a path that has already gone —
     * this runs when an upload is REPLACED, and a missing old file must never be the
     * reason a perfectly good new one fails to save.
     */
    public function forget(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        Storage::disk($this->disk())->delete($path);
    }

    public function exists(string $path): bool
    {
        return Storage::disk($this->disk())->exists($path);
    }

    public function disk(): string
    {
        return (string) config('library.disk', 'local');
    }

    private function put(UploadedFile $file, string $directory): string
    {
        $stored = $file->store($directory, $this->disk());

        // store() returns false on a write failure. Letting that through would write
        // a row whose stored_path is the string "" and 404 for every visitor.
        if (! is_string($stored) || $stored === '') {
            throw new \RuntimeException('Could not write the uploaded file to the '.$this->disk().' disk.');
        }

        return $stored;
    }
}
