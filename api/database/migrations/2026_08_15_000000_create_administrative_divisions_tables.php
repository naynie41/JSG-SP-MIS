<?php

declare(strict_types=1);

use App\Domain\Registry\Enums\Lga;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Jigawa administrative divisions as REFERENCE DATA: `lgas` (27) and `wards`, with
 * `wards.lga_id` giving the LGA → wards hierarchy the cascading selector walks.
 *
 * `code` is the join key and is deliberately the SAME slug already used by
 * {@see Lga} (`birnin_kudu`) and by `geo_boundaries.code`.
 * That is what lets the deferred free-text → lookup migration of
 * `beneficiaries.lga` / `.ward` happen later without rework: the existing free-text
 * values are already slugs of these names, so the backfill is a join on `code`, not a
 * fuzzy re-match. Ward codes are only unique WITHIN an LGA (ward names repeat across
 * the state), so the deferred backfill must resolve a ward through its LGA — hence
 * `unique(lga_id, code)` rather than a global unique.
 *
 * Geometry is optional and nullable on both tables (FR-GIS-01): a portable GeoJSON
 * `geometry` JSON column that works everywhere including sqlite, plus a PostGIS
 * `geom` column on PostgreSQL. Both stay NULL until boundaries are supplied — these
 * tables carry the hierarchy, `geo_boundaries` carries the shapes, and `code` is the
 * seam between them.
 *
 * Beneficiary/household columns are NOT touched here. That is a separate, deferred
 * step.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lgas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();  // slug — joins to the Lga enum + geo_boundaries.code
            $table->string('name');
            $table->string('state')->default('Jigawa');
            $table->decimal('latitude', 10, 7)->nullable();   // centroid, optional
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('geometry')->nullable();             // GeoJSON, portable (sqlite-safe)
            $table->timestamps();

            $table->index('name');
        });

        Schema::create('wards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('lga_id')->constrained('lgas')->cascadeOnDelete();
            $table->string('code');   // slug, unique within its LGA only
            $table->string('name');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('geometry')->nullable();
            $table->timestamps();

            // Ward names repeat across LGAs, so the key is (lga, code) — never code alone.
            $table->unique(['lga_id', 'code']);
            $table->index('code');
            $table->index('name');
        });

        // PostGIS geometry — PostgreSQL only, nullable, filled if/when boundaries are
        // supplied (FR-GIS-01). Mirrors the `geo_boundaries.geom` column exactly.
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE lgas ADD COLUMN geom geometry(MultiPolygon, 4326)');
            DB::statement('ALTER TABLE wards ADD COLUMN geom geometry(MultiPolygon, 4326)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('wards'); // FK child first
        Schema::dropIfExists('lgas');
    }
};
