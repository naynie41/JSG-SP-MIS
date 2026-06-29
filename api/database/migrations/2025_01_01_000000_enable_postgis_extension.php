<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Enables the PostGIS extension. This runs first (dated 2025_01_01) so that
 * later migrations can declare geometry/geography columns for GIS features.
 *
 * Only applies on PostgreSQL; on other drivers it is a no-op so the test
 * suite can still run against sqlite if ever needed.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('CREATE EXTENSION IF NOT EXISTS postgis');
    }

    public function down(): void
    {
        // Intentionally a no-op. PostGIS is shared database infrastructure: the
        // postgis/postgis image also installs postgis_topology and
        // postgis_tiger_geocoder which depend on it, so dropping it here would
        // fail (or require CASCADE and tear down more than this migration owns).
        // Leaving the extension in place keeps rollbacks safe and reversible.
    }
};
