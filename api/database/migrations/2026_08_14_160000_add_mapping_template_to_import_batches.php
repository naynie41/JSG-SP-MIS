<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Records WHICH saved mapping a batch used (CLAUDE.md §11, FR-AUD-01).
 *
 * `column_map` already records the mapping itself, but not where it came from. When a
 * template turns out to be wrong — the MDA moved a column and nobody noticed — the
 * question is "which other imports used that same template", and answering it by
 * comparing JSON blobs is not an answer anyone will actually get.
 *
 * `nullOnDelete`: deleting a template must not erase the history of imports that used
 * it. The batch keeps its own `column_map` regardless, so the mapping survives even when
 * the template does not.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_batches', function (Blueprint $table) {
            $table->uuid('mapping_template_id')->nullable();

            $table->foreign('mapping_template_id')->references('id')->on('import_mapping_templates')->nullOnDelete();
            $table->index('mapping_template_id');
        });
    }

    public function down(): void
    {
        Schema::table('import_batches', function (Blueprint $table) {
            $table->dropForeign(['mapping_template_id']);
            $table->dropIndex(['mapping_template_id']);
            $table->dropColumn('mapping_template_id');
        });
    }
};
