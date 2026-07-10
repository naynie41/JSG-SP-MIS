<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Activity binding for imports (PRD §9/§10, FR-REG-10/FR-PRG-05). The standalone
 * Import Center binds every upload to a registered activity at upload time (required
 * there). The activity-creation wizard (§10 "optional inline upload") stages an
 * UNBOUND preview batch — `activity_id` null, the intended activity held in
 * `draft_activity` — then creates the activity and binds it atomically at confirm.
 * So the column is nullable here; activity-first is asserted at COMMIT (a batch
 * cannot commit unbound). The resulting intervention is recorded under the activity.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_batches', function (Blueprint $table) {
            $table->uuid('activity_id')->nullable()->after('source');
            $table->json('draft_activity')->nullable()->after('activity_id'); // wizard: activity params until confirm
            $table->foreign('activity_id')->references('id')->on('activities')->cascadeOnDelete();
            $table->index('activity_id');
        });
    }

    public function down(): void
    {
        Schema::table('import_batches', function (Blueprint $table) {
            $table->dropForeign(['activity_id']);
            $table->dropIndex(['activity_id']);
            $table->dropColumn(['activity_id', 'draft_activity']);
        });
    }
};
