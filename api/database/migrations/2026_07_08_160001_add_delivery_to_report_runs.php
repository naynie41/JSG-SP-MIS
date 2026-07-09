<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Scheduled-report delivery on a run (PRD FR-RPT-04). A run produced by a schedule
 * records the schedule it came from and the validated recipient users it should be
 * delivered to — so a recipient (not only the requester) can access the file, and the
 * delivery listener knows who to notify.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report_runs', function (Blueprint $table) {
            $table->uuid('schedule_id')->nullable()->after('requested_mda_id');
            $table->json('recipient_user_ids')->nullable()->after('schedule_id');
            $table->string('delivery')->nullable()->after('recipient_user_ids'); // link | attachment (scheduled runs)
        });
    }

    public function down(): void
    {
        Schema::table('report_runs', function (Blueprint $table) {
            $table->dropColumn(['schedule_id', 'recipient_user_ids', 'delivery']);
        });
    }
};
