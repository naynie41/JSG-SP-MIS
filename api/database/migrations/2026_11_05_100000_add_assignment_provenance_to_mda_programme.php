<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Makes `mda_programme` the ASSIGNMENT the catalog is read through (§10, revised
 * 2026-11-05) rather than a record nothing consulted.
 *
 * The table already held 112 rows from the MDA inventory, but no application code
 * read them: every MDA saw the whole catalog in every programme picker. Reading them
 * is the behaviour change; this migration only makes the data safe to read from.
 *
 * Two parts, and the second is the important one:
 *
 *  - `assigned_by` records WHO assigned a programme to an MDA. The existing `source`
 *    column says where an association came from (a seeded inventory row, an MDA's own
 *    approved programme) but not who decided it, and an assignment that gates what an
 *    MDA may deliver needs an actor like any other administrative act.
 *  - **Backfill from activities.** Every (MDA, programme) pair that ALREADY has an
 *    activity becomes an assignment, `source = 'derived'`. Without this, turning the
 *    filter on would hide programmes MDAs are demonstrably running: on the development
 *    database all 18 activities sit under programmes the inventory never mentioned, so
 *    every one of them would vanish from its own MDA's picker. The claim is honest —
 *    an MDA that has built an activity under a programme runs that programme.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mda_programme', function (Blueprint $table): void {
            // Nullable, and nullable forever: a row from the inventory seed or from an
            // approval has no assigning administrator, and inventing one would put a
            // name against a decision that person never made.
            $table->uuid('assigned_by')->nullable()->after('source');
            $table->foreign('assigned_by')->references('id')->on('users')->nullOnDelete();
        });

        $this->backfillFromActivities();
    }

    public function down(): void
    {
        // Only the rows this migration invented. An inventory or approval row predates
        // it and is not ours to remove.
        DB::table('mda_programme')->where('source', 'derived')->delete();

        Schema::table('mda_programme', function (Blueprint $table): void {
            $table->dropForeign(['assigned_by']);
            $table->dropColumn('assigned_by');
        });
    }

    /**
     * One assignment per distinct (owner_mda_id, programme_id) found in `activities`
     * that is not already recorded.
     *
     * Built in PHP rather than as an INSERT … SELECT so the UUID primary key is
     * generated the same way the application generates it, on both PostgreSQL and the
     * sqlite test database.
     */
    private function backfillFromActivities(): void
    {
        $existing = DB::table('mda_programme')
            ->select('mda_id', 'programme_id')
            ->get()
            ->map(fn (object $row) => $row->mda_id.'|'.$row->programme_id)
            ->all();

        $existing = array_fill_keys($existing, true);
        $now = now();
        $rows = [];

        DB::table('activities')
            ->select('owner_mda_id', 'programme_id')
            ->whereNotNull('owner_mda_id')
            ->whereNotNull('programme_id')
            ->distinct()
            ->orderBy('owner_mda_id')
            ->orderBy('programme_id')
            ->each(function (object $activity) use (&$rows, &$existing, $now): void {
                $key = $activity->owner_mda_id.'|'.$activity->programme_id;

                if (isset($existing[$key])) {
                    return;
                }

                // Guard within this run too: `distinct` makes duplicates unlikely, but
                // the unique index would abort the whole migration over one of them.
                $existing[$key] = true;

                $rows[] = [
                    'id' => (string) Str::uuid(),
                    'mda_id' => $activity->owner_mda_id,
                    'programme_id' => $activity->programme_id,
                    'source' => 'derived',
                    'assigned_by' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            });

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('mda_programme')->insert($chunk);
        }
    }
};
