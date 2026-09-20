<?php

declare(strict_types=1);

use App\Domain\Library\Enums\LibraryItemKind;
use App\Domain\Library\Enums\LibraryItemStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The public resource library (PRD §7.14) — policies, guidelines, tools and reports
 * the State publishes for anyone to read, signed in or not.
 *
 * Deliberately NOT MDA-scoped. Every other table that holds a file belongs to the
 * organisation that created it; this one belongs to the State and is served to the
 * open internet, so `owner_mda_id` would be a column that always answered the same
 * question wrongly. Publishing is a System Administrator act (CLAUDE.md §12).
 *
 * Two shapes in one table, kept apart by `kind`:
 *
 *  - `file` — an upload. The file columns are populated and `external_url` is null.
 *  - `link` — a pointer elsewhere. `external_url` is populated and the file columns
 *    are null.
 *
 * A CHECK constraint enforces that rather than trusting the application, because the
 * public download endpoint reads `stored_path` and a half-populated row would be a
 * 500 on a page with no authentication in front of it.
 *
 * `status` is the publication lifecycle and is the ONLY thing standing between a
 * draft and the open internet — see LibraryItem::scopePublished(). Archived rows are
 * retained rather than deleted (CLAUDE.md §10: never hard-delete a record carrying
 * history — here, download counts and audit trail).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('library_items', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->string('title');
            $table->text('description')->nullable();
            // Config-driven (config/library.php), not a foreign key: the taxonomy is
            // fixed editorial vocabulary, so a lookup table would add a CRUD screen,
            // an empty-category state and a delete rule for no gain.
            $table->string('category', 40);
            // Pinned into the "Key Content" band above the main grid.
            $table->boolean('featured')->default(false);

            $table->string('kind', 10)->default(LibraryItemKind::File->value);

            // kind = file. Stored on the PRIVATE disk and streamed by a controller:
            // storage/app/public is NOT a persisted volume in production, so anything
            // written there is lost on the next redeploy while the row survives.
            $table->string('stored_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->string('mime_type', 150)->nullable();
            $table->unsignedBigInteger('size_bytes')->nullable();
            $table->string('checksum_sha256', 64)->nullable();

            // kind = link.
            $table->string('external_url', 2048)->nullable();

            // Optional for either kind; the card falls back to a category placeholder.
            $table->string('thumbnail_path')->nullable();
            $table->string('thumbnail_mime', 150)->nullable();

            $table->string('status', 20)->default(LibraryItemStatus::Draft->value);
            $table->timestamp('published_at')->nullable();

            $table->unsignedBigInteger('download_count')->default(0);

            $table->uuid('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // The public list filters on status first and orders by featured then
            // published_at; this covers that path.
            $table->index(['status', 'featured', 'published_at']);
            $table->index('category');
        });

        // Belt as well as braces: the shape is enforced by the request and the model
        // (both covered by tests), and ALSO in the database where the driver allows
        // it. The public endpoints have no authentication in front of them, so a
        // half-written row is a 500 on a page anyone can reach.
        //
        // Postgres only. SQLite cannot ADD CONSTRAINT after CREATE TABLE, and the
        // test suite runs on SQLite — so this guard means the constraint is NOT
        // exercised by the suite. That is why the application-level rule is the
        // primary defence here and the constraint is the backstop, not the reverse.
        if (DB::getDriverName() === 'pgsql') {
            $file = LibraryItemKind::File->value;
            $link = LibraryItemKind::Link->value;
            DB::statement(<<<SQL
                ALTER TABLE library_items ADD CONSTRAINT library_items_kind_shape CHECK (
                    (kind = '{$file}' AND stored_path IS NOT NULL AND external_url IS NULL)
                    OR
                    (kind = '{$link}' AND external_url IS NOT NULL AND stored_path IS NULL)
                )
            SQL);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('library_items');
    }
};
