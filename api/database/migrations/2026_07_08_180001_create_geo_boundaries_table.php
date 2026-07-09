<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Administrative boundaries for GIS (PRD FR-GIS-01): LGA (admin level 2) and Ward
 * (admin level 3). The raw GeoJSON `geometry` is stored as JSON so the choropleth can
 * be rendered portably (and works on sqlite in tests). On PostgreSQL a PostGIS `geom`
 * column is also populated — the clean extension point for heat maps (FR-GIS-02) and
 * point-in-polygon work. `code` is a normalised slug matched to registry LGA/Ward
 * values; `parent_code` links a ward to its LGA.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('geo_boundaries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('level');       // lga | ward
            $table->string('code');        // slug, matches registry lga/ward values
            $table->string('name');
            $table->string('parent_code')->nullable(); // ward → lga slug
            $table->json('geometry');      // raw GeoJSON geometry (drives the choropleth)
            $table->timestamps();

            $table->unique(['level', 'code']);
            $table->index('level');
            $table->index('parent_code');
        });

        // PostGIS geometry — PostgreSQL only (heat-map / spatial extension point).
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE geo_boundaries ADD COLUMN geom geometry(MultiPolygon, 4326)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('geo_boundaries');
    }
};
