<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Offline-capture groundwork (PRD FR-REG-08). A client-supplied `idempotency_key`
 * lets a future offline client sync a queued registration exactly once: a second
 * submission with the same key (within the same MDA) resolves to the existing
 * record instead of creating a duplicate. Nullable — online paths need not use it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->string('idempotency_key')->nullable()->after('original_record_id');
        });

        // Unique per owning MDA, only when present (portable pgsql + sqlite).
        DB::statement('CREATE UNIQUE INDEX beneficiaries_idempotency_key_unique ON beneficiaries (owner_mda_id, idempotency_key) WHERE idempotency_key IS NOT NULL');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS beneficiaries_idempotency_key_unique');

        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropColumn('idempotency_key');
        });
    }
};
