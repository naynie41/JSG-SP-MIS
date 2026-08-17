<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Drops the single `activities.lga` / `activities.ward` pair, now replaced by the
 * `activity_locations` set (which the preceding migration backfilled).
 *
 * Kept as a separate migration so the backfill has committed before anything is
 * destroyed, and so a failed backfill stops the sequence with the columns intact.
 *
 * `location_description` is deliberately KEPT: it is free prose ("along the Hadejia
 * road"), not an admin area, and the location set does not replace it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['lga', 'ward']);
        });
    }

    /**
     * Restores the columns and repopulates each activity from ONE of its locations.
     *
     * A set cannot round-trip into a single field, so this is lossy by nature: the
     * first location (ordered for determinism) wins and the rest stay only in
     * `activity_locations`. Recorded here rather than silently, because a rollback that
     * appears to restore the old state while dropping locations would mislead.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('lga')->nullable()->after('target_beneficiaries');
            $table->string('ward')->nullable()->after('lga');
        });

        if (! Schema::hasTable('activity_locations')) {
            return;
        }

        $first = DB::table('activity_locations')
            ->join('lgas', 'lgas.id', '=', 'activity_locations.lga_id')
            ->leftJoin('wards', 'wards.id', '=', 'activity_locations.ward_id')
            ->orderBy('activity_locations.activity_id')
            ->orderBy('lgas.code')
            ->orderByRaw('wards.code is null desc') // whole-LGA row first, if present
            ->orderBy('wards.code')
            ->get(['activity_locations.activity_id', 'lgas.code as lga_code', 'wards.name as ward_name']);

        $seen = [];
        foreach ($first as $row) {
            if (isset($seen[$row->activity_id])) {
                continue;
            }
            $seen[$row->activity_id] = true;

            DB::table('activities')
                ->where('id', $row->activity_id)
                ->update(['lga' => $row->lga_code, 'ward' => $row->ward_name]);
        }
    }
};
