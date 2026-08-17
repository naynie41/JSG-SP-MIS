<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Cross-MDA access grants are REVOKED, never deleted (NFR-PRV-01, FR-AUD-01).
 *
 * Revoking previously hard-deleted the row. That erased the evidence that the access had
 * ever existed — precisely the opposite of what an auditable PII-access trail is for. A
 * reviewer asking "did anyone outside this MDA hold access to these records last year,
 * and when did it end?" would find nothing, and nothing would distinguish that from
 * access never having been granted.
 *
 * The service-request grant (`beneficiary_service_grants`) already worked this way; this
 * brings the administrative grant into line so both cross-MDA paths keep their history.
 *
 * The unique key becomes PARTIAL for the same reason it is partial there: without the
 * `WHERE revoked_at IS NULL` predicate, a retained revoked row would permanently block
 * that user from ever being granted access to that MDA again.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mda_access_grants', function (Blueprint $table) {
            $table->timestamp('revoked_at')->nullable();
            $table->uuid('revoked_by')->nullable();
            $table->string('revocation_reason')->nullable();

            $table->foreign('revoked_by')->references('id')->on('users')->nullOnDelete();
        });

        // Replace the plain unique with one that only constrains ACTIVE grants.
        Schema::table('mda_access_grants', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'mda_id']);
        });

        DB::statement('CREATE UNIQUE INDEX mda_access_grants_one_active ON mda_access_grants (user_id, mda_id) WHERE revoked_at IS NULL');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS mda_access_grants_one_active');

        Schema::table('mda_access_grants', function (Blueprint $table) {
            $table->dropForeign(['revoked_by']);
            $table->dropColumn(['revoked_at', 'revoked_by', 'revocation_reason']);
            $table->unique(['user_id', 'mda_id']);
        });
    }
};
