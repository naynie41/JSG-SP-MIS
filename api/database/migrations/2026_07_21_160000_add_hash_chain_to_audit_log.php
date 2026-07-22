<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Hash-chaining for the audit log (NFR-AUD-01 — Phase 7 hardening).
 *
 * Every new entry records its position in a single chain, the previous entry's
 * hash, and a SHA-256 over its own canonical payload + that previous hash. Any
 * later modification, deletion, or reordering of a chained row breaks every hash
 * after it, which `php artisan audit:verify-chain` detects.
 *
 * Existing rows are NOT backfilled — the log is append-only (the DB triggers
 * forbid UPDATE), so pre-hardening rows simply predate the chain (position NULL).
 * The chain starts at the first row written after this migration.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_log', function (Blueprint $table) {
            $table->unsignedBigInteger('chain_position')->nullable();
            $table->string('prev_hash', 64)->nullable();
            $table->string('entry_hash', 64)->nullable();
        });

        // Partial unique: one row per position; a concurrent-writer race becomes a
        // loud constraint failure (retried by the model), never a silent fork.
        DB::statement('CREATE UNIQUE INDEX audit_log_chain_position_unique ON audit_log (chain_position) WHERE chain_position IS NOT NULL');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS audit_log_chain_position_unique');

        Schema::table('audit_log', function (Blueprint $table) {
            $table->dropColumn(['chain_position', 'prev_hash', 'entry_hash']);
        });
    }
};
