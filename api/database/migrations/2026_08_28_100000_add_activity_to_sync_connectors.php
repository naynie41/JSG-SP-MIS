<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The activity a connector binds its synced rows to (activity-first, FR-DSH-02).
 *
 * An upload names the activity that brought people in; sync did not, so the same records
 * arriving through a connector landed in the registry attached to nothing. This is the
 * connector's equivalent of the activity an officer picks when uploading a file.
 *
 * NULLABLE on purpose. Existing connectors have no activity, and a NOT NULL column would
 * either fail the migration or force a fabricated value onto records already synced. The
 * requirement is enforced at RUN time instead: a connector without an activity is held
 * and ingests nothing, which is visible and fixable, where a bad backfill would not be.
 *
 * `nullOnDelete` rather than cascade: deleting an activity must never delete the
 * connector configuration along with it. The connector simply becomes unbound, and the
 * run-time hold then makes that state obvious.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sync_connectors', function (Blueprint $table) {
            $table->foreignUuid('activity_id')->nullable()->after('owner_mda_id')
                ->constrained('activities')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sync_connectors', function (Blueprint $table) {
            $table->dropConstrainedForeignId('activity_id');
        });
    }
};
