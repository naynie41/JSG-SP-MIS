<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lifecycle provenance for activities (PRD FR-PRG-10, §10 archive-never-delete).
 *
 * `programmes` has carried `archived_at` / `archived_by` / `archive_reason` since
 * archiving was introduced; `activities` never did. An activity could be archived —
 * `ActivityController@archive` wrote the status directly — leaving no record of who
 * did it, when, or why, and no audit entry at all. That is the gap this closes, and
 * it matters independently of the scheduled completion the same release adds.
 *
 * `completed_at` is new to both. It records when an activity's timeline was observed
 * to have ended, which is NOT the same as `ends_on`: the end date is a plan, and the
 * completion stamp is the moment the system acted on it. Keeping them apart means a
 * late-running sweep, a backfill or a changed end date can never rewrite history.
 *
 * Backfill is deliberately none. Existing archived activities keep null provenance
 * rather than being stamped with a guessed timestamp and a null actor — "we do not
 * know who archived this" is the truth, and inventing a value would bury it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table): void {
            $table->timestamp('completed_at')->nullable()->after('status');
            $table->timestamp('archived_at')->nullable()->after('completed_at');
            $table->uuid('archived_by')->nullable()->after('archived_at');
            $table->text('archive_reason')->nullable()->after('archived_by');

            $table->foreign('archived_by')->references('id')->on('users')->nullOnDelete();

            // The sweep selects on (status, ends_on) every night; this is that query.
            $table->index(['status', 'ends_on']);
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table): void {
            $table->dropForeign(['archived_by']);
            $table->dropIndex(['status', 'ends_on']);
            $table->dropColumn(['completed_at', 'archived_at', 'archived_by', 'archive_reason']);
        });
    }
};
