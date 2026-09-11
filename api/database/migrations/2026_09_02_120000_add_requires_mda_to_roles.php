<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Which roles are MDA-SCOPED (PRD FR-UAM-02/03).
 *
 * An MDA Admin operates one MDA's workspace and must belong to one; Executive,
 * SP Coordination, M&E Officer, Development Partner and System Administrator work at
 * state level and must NOT be tied to a single MDA.
 *
 * Modelled on `roles.requires_mfa`, which already carries exactly this kind of
 * per-role rule. A column rather than a hardcoded role list in the validator because
 * the frontend keeps its own `MDA_ROLES`, and two hardcoded lists drift; this way
 * both read the same value from `/roles`.
 *
 * Backfilled for `mda_admin` here so the rule holds even where the seeder is not
 * re-run — the migration must leave the database consistent on its own.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table): void {
            $table->boolean('requires_mda')->default(false)->after('requires_mfa');
        });

        DB::table('roles')->where('key', 'mda_admin')->update(['requires_mda' => true]);
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table): void {
            $table->dropColumn('requires_mda');
        });
    }
};
