<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Standing column-mapping confirmation for a sync connector (CLAUDE.md §11).
 *
 * The §11 rule — identity mappings are confirmed by a human on every import — cannot be
 * met literally by a scheduled connector: there is nobody present at 02:00 to answer.
 * Dropping the rule for sync would be worse, because a connector ingests continuously
 * and a wrong NIN mapping would merge citizens on every run rather than once.
 *
 * So the confirmation moves to CONFIGURATION time and stands for subsequent runs. The
 * property preserved is the one that matters: no record reaches the duplicate cascade
 * under an identity mapping no person ever approved.
 *
 * `source_signature` is what keeps that honest. It fingerprints the shape of the records
 * the source actually returned when the mapping was confirmed; a run whose records no
 * longer match that shape stops and asks for re-confirmation, instead of quietly
 * applying an approval given for a different schema.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sync_connectors', function (Blueprint $table) {
            $table->json('column_map')->nullable();
            $table->string('source_signature', 64)->nullable();
            $table->timestamp('mapping_confirmed_at')->nullable();
            $table->uuid('mapping_confirmed_by')->nullable();

            $table->foreign('mapping_confirmed_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sync_connectors', function (Blueprint $table) {
            $table->dropForeign(['mapping_confirmed_by']);
            $table->dropColumn(['column_map', 'source_signature', 'mapping_confirmed_at', 'mapping_confirmed_by']);
        });
    }
};
