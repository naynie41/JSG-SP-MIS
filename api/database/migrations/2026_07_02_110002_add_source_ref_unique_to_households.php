<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * One household per (owning MDA, source household key). Makes import-driven
 * household formation idempotent: re-importing the same source household resolves
 * to the existing record instead of creating a duplicate. Portable pgsql + sqlite.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE UNIQUE INDEX households_source_ref_unique ON households (owner_mda_id, original_record_id) WHERE original_record_id IS NOT NULL');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS households_source_ref_unique');
    }
};
