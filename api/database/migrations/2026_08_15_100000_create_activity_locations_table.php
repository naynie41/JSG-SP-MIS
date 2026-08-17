<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * An activity's DECLARED location set — many LGAs, and many wards within each.
 *
 * One row per selected ward. A row with `ward_id = NULL` means "the whole LGA", so
 * wards are optional per LGA. Multiple LGAs = rows with distinct `lga_id`; multiple
 * wards in one LGA = rows sharing an `lga_id`.
 *
 * DESCRIPTIVE ONLY. This is a planning and coverage-reporting statement about where an
 * activity intends to operate. It is NOT a constraint on the beneficiaries uploaded
 * under the activity, and nothing in this system checks a beneficiary's LGA/ward
 * against it — deliberately, because the declared set is a plan and the uploaded people
 * are the fact, and a mismatch means the plan changed, not that the data is wrong.
 *
 * Replaces the single `activities.lga` / `activities.ward` free-text pair, which could
 * express only one place and joined to nothing. These are real foreign keys into the
 * GEO.1 lookups, so coverage/GIS aggregations can group by `lga_id` / `ward_id`.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('activity_id')->constrained('activities')->cascadeOnDelete();
            $table->foreignUuid('lga_id')->constrained('lgas')->restrictOnDelete();
            // NULL = the whole LGA. Restricted rather than cascading: silently dropping
            // a declared ward because reference data was reloaded would rewrite history.
            $table->foreignUuid('ward_id')->nullable()->constrained('wards')->restrictOnDelete();
            $table->timestamps();

            $table->index('activity_id');
            $table->index('lga_id');
            $table->index('ward_id');

            // Stops the same ward being declared twice for one activity. NULLs are
            // distinct in a SQL unique index, so this does NOT cover the whole-LGA row —
            // that case needs the partial index below.
            $table->unique(['activity_id', 'lga_id', 'ward_id'], 'activity_locations_unique_ward');
        });

        // One whole-LGA row per (activity, lga). Partial index because `ward_id IS NULL`
        // is exactly the case the unique index above cannot see.
        DB::statement(
            'CREATE UNIQUE INDEX activity_locations_unique_whole_lga
             ON activity_locations (activity_id, lga_id) WHERE ward_id IS NULL'
        );

        $this->backfill();
    }

    /**
     * Migrates each activity's single LGA/Ward into one `activity_locations` row.
     *
     * The old values are free text (an LGA slug and an unconstrained ward string), so
     * they are resolved against the GEO.1 lookups by `code`. A ward is resolved WITHIN
     * its LGA, never state-wide — ward names repeat across Jigawa, so a state-wide match
     * would attach activities to the wrong place.
     *
     * Anything unresolved is recorded in the audit log with its raw values, so the
     * information is not lost when the columns are dropped.
     */
    private function backfill(): void
    {
        if (! Schema::hasColumn('activities', 'lga')) {
            return; // already migrated
        }

        $activities = DB::table('activities')
            ->whereNotNull('lga')
            ->where('lga', '!=', '')
            ->get(['id', 'lga', 'ward']);

        if ($activities->isEmpty()) {
            return;
        }

        // The realistic failure this guards: migrating before the GEO.1 dataset is
        // loaded. Every activity would be "unresolved", and the next migration drops the
        // columns — turning a load-order mistake into permanent data loss. Refuse
        // instead, with the fix in the message.
        if (DB::table('lgas')->count() === 0) {
            throw new RuntimeException(
                "Cannot migrate activity locations: {$activities->count()} activities have an LGA set, ".
                "but the `lgas` lookup table is empty.\n\n".
                "Load the reference data first, then re-run this migration:\n".
                "  php artisan reference:load-divisions\n\n".
                'Migrating now would leave every activity unresolved and the old columns would then '.
                'be dropped, losing the values permanently.'
            );
        }

        $lgaIds = DB::table('lgas')->pluck('id', 'code');          // code => id
        $wards = DB::table('wards')->get(['id', 'lga_id', 'code']); // resolved per LGA below

        $rows = [];
        $unresolved = [];
        $now = now();

        foreach ($activities as $activity) {
            $lgaCode = $this->slug((string) $activity->lga);
            $lgaId = $lgaIds[$lgaCode] ?? null;

            if ($lgaId === null) {
                $unresolved[] = ['activity_id' => $activity->id, 'lga' => $activity->lga, 'ward' => $activity->ward, 'reason' => 'unknown_lga'];

                continue;
            }

            $wardId = null;
            $wardRaw = trim((string) ($activity->ward ?? ''));

            if ($wardRaw !== '') {
                $wardCode = $this->slug($wardRaw);
                $match = $wards->first(fn (object $w): bool => $w->lga_id === $lgaId && $w->code === $wardCode);

                if ($match === null) {
                    // The LGA resolved but the ward did not. Recorded as a whole-LGA row
                    // rather than dropped: the activity demonstrably operates in that
                    // LGA, and that much is true even when the ward string is not.
                    $unresolved[] = ['activity_id' => $activity->id, 'lga' => $activity->lga, 'ward' => $activity->ward, 'reason' => 'unknown_ward_kept_whole_lga'];
                } else {
                    $wardId = $match->id;
                }
            }

            $rows[] = [
                'id' => (string) Str::uuid7(),
                'activity_id' => $activity->id,
                'lga_id' => $lgaId,
                'ward_id' => $wardId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if ($rows !== []) {
            DB::table('activity_locations')->insert($rows);
        }

        $this->report(count($rows), $unresolved);
    }

    /**
     * Records the outcome in the audit log — the system's permanent record — so the raw
     * values of anything unresolved survive the column drop and someone can fix them by
     * hand later.
     *
     * @param  list<array<string, mixed>>  $unresolved
     */
    private function report(int $migrated, array $unresolved): void
    {
        DB::table('audit_log')->insert([
            'id' => (string) Str::uuid7(),
            'actor_id' => null,
            'actor_mda_id' => null,
            'action' => 'activity.locations.migrated',
            'entity_type' => 'activity_location',
            'entity_id' => null,
            'before' => null,
            'after' => (string) json_encode([
                'migrated_rows' => $migrated,
                'unresolved_count' => count($unresolved),
                'unresolved' => $unresolved,
            ]),
            'created_at' => now(),
        ]);

        if ($unresolved !== []) {
            // Visible during `php artisan migrate`, not just buried in the table.
            fwrite(STDERR, sprintf(
                "\n  [activity locations] %d migrated, %d unresolved — see audit_log action=activity.locations.migrated\n",
                $migrated,
                count($unresolved),
            ));
        }
    }

    /** The registry slug, matching `lgas.code` / `wards.code`. */
    private function slug(string $value): string
    {
        return Str::of($value)->lower()->replaceMatches('/[^a-z0-9]+/', '_')->trim('_')->value();
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_locations');
    }
};
