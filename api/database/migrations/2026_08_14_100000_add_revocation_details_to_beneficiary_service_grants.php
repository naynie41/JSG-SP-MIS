<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Records WHO revoked a cross-MDA read grant and WHY (PRD FR-OWN-07, FR-AUD-01).
 *
 * `revoked_at` already existed and was honoured everywhere, but nothing recorded the
 * actor or the reason — so a withdrawal of access to citizen data could be seen to have
 * happened without being accountable to anyone. The audit log carries the same facts;
 * these columns keep them on the grant itself, which is what the data-sharing oversight
 * view reads.
 *
 * Revocation stays SOFT (a timestamp, never a delete): the grant row is the record that
 * this cross-MDA access episode occurred, and deleting it would erase that history. The
 * existing partial unique index on `(beneficiary_id, mda_id) WHERE revoked_at IS NULL`
 * means a revoked row also frees the pair for a future re-grant.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiary_service_grants', function (Blueprint $table) {
            $table->uuid('revoked_by')->nullable();
            $table->text('revocation_reason')->nullable();

            $table->foreign('revoked_by')->references('id')->on('users')->nullOnDelete();
        });

        self::restoreOneActiveIndex();
    }

    public function down(): void
    {
        Schema::table('beneficiary_service_grants', function (Blueprint $table) {
            $table->dropForeign(['revoked_by']);
            $table->dropColumn(['revoked_by', 'revocation_reason']);
        });

        self::restoreOneActiveIndex();
    }

    /**
     * Re-create the PARTIAL unique index that allows one ACTIVE grant per
     * (beneficiary, MDA) while keeping every revoked row as history.
     *
     * Adding a column with a foreign key makes SQLite rebuild the whole table, and the
     * rebuild reconstructs indexes from introspection that does not carry a partial
     * index's `WHERE` predicate — so `..._one_active` silently comes back as a PLAIN
     * unique index. That is worse than a cosmetic difference: with the predicate gone, a
     * revoked grant permanently blocks the same MDA from ever being granted access to
     * that beneficiary again, because the revoked row still occupies the pair.
     *
     * Recreating it explicitly is harmless where no rebuild happened (PostgreSQL keeps
     * partial indexes across `ADD COLUMN`), and necessary where one did.
     */
    private static function restoreOneActiveIndex(): void
    {
        DB::statement('DROP INDEX IF EXISTS beneficiary_service_grants_one_active');
        DB::statement('CREATE UNIQUE INDEX beneficiary_service_grants_one_active ON beneficiary_service_grants (beneficiary_id, mda_id) WHERE revoked_at IS NULL');
    }
};
