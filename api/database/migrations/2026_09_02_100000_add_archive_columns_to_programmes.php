<?php

declare(strict_types=1);

use App\Domain\Programme\Enums\ProgrammeStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Archive-as-delete for the programme catalog (PRD §10).
 *
 * A programme carries activities, enrolments, ledger entries and graduation history,
 * so it is never destroyed — it is archived. `ProgrammeStatus::Archived` already
 * existed but recorded only THAT a programme was archived, never when, by whom, or
 * why; this adds that provenance.
 *
 * `archived_at` is the AUTHORITATIVE flag. The status enum is kept in step by
 * ProgrammeArchiver, which is the only writer of either — two independent sources of
 * truth for the same fact drift apart, and the query scope has to trust one of them.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programmes', function (Blueprint $table): void {
            $table->timestamp('archived_at')->nullable()->after('status');
            $table->uuid('archived_by')->nullable()->after('archived_at');
            $table->string('archive_reason', 500)->nullable()->after('archived_by');

            $table->foreign('archived_by')->references('id')->on('users')->nullOnDelete();
            // The scope filters on this on every read, so it earns an index.
            $table->index('archived_at');
        });

        // Backfill: anything already carrying the archived STATUS is genuinely
        // archived, and must not become visible again the moment the scope goes live.
        // `updated_at` is the closest honest timestamp we hold — there is no record of
        // the real archive time, and inventing `now()` would misdate history.
        DB::table('programmes')
            ->where('status', ProgrammeStatus::Archived->value)
            ->whereNull('archived_at')
            ->update(['archived_at' => DB::raw('updated_at')]);
    }

    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table): void {
            $table->dropForeign(['archived_by']);
            $table->dropIndex(['archived_at']);
            $table->dropColumn(['archived_at', 'archived_by', 'archive_reason']);
        });
    }
};
