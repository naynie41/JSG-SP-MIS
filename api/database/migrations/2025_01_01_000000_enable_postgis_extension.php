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
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('DROP EXTENSION IF EXISTS postgis');
    }
};
