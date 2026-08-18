<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Records which EARLIER BATCH a pre-filled column mapping was taken from.
 *
 * Saving a named template is optional, so the common case is an MDA that simply uploads
 * the same export again — same columns, no template. `mapping_template_id` cannot
 * express that provenance, and without it the reviewer is asked to confirm a mapping
 * that appeared from nowhere.
 *
 * Nullable and never cascading: the pre-fill is a historical fact about THIS batch, and
 * deleting the batch it was copied from must not rewrite it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_batches', function (Blueprint $table) {
            $table->uuid('mapping_prefilled_from_id')->nullable()->after('mapping_template_id');
            $table->index('mapping_prefilled_from_id');
        });
    }

    public function down(): void
    {
        Schema::table('import_batches', function (Blueprint $table) {
            $table->dropIndex(['mapping_prefilled_from_id']);
            $table->dropColumn('mapping_prefilled_from_id');
        });
    }
};
