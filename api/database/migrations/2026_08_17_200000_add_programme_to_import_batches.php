<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The catalog PROGRAMME an import is for.
 *
 * Revises the activity-first upload rule (CLAUDE.md §9): an upload must name a
 * programme, and MAY name an activity. Registering people under a catalog programme is a
 * complete act; the activity adds *which MDA-run instance* delivered to them, which an
 * intake often does not know yet. Requiring one forced officers to invent placeholder
 * activities, which is worse than recording the truth that none applies yet.
 *
 * Nullable in the schema for two reasons, both about not lying:
 *  - existing batches predate the column and their programme is derivable from their
 *    activity, so backfilling a value here would duplicate a fact rather than record one;
 *  - a batch bound to an activity takes its programme FROM that activity, so the column
 *    stays null there and the two can never disagree.
 *
 * The real rule — a batch must name a programme or an activity — is enforced where it can
 * be stated properly: the upload request, and `ImportCommitter` at commit time.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_batches', function (Blueprint $table) {
            $table->uuid('programme_id')->nullable()->after('activity_id');
            $table->index('programme_id');
        });

        // Programmes are a global catalog (§10) and are never deleted, only archived;
        // restrict rather than cascade so an import's provenance cannot be erased.
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('import_batches', function (Blueprint $table) {
                $table->foreign('programme_id')->references('id')->on('programmes')->restrictOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('import_batches', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign(['programme_id']);
            }
            $table->dropIndex(['programme_id']);
            $table->dropColumn('programme_id');
        });
    }
};
