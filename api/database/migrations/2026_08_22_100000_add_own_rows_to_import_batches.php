<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A fourth commit outcome: rows that matched a beneficiary the uploading MDA ALREADY
 * OWNS (a re-upload of its own data).
 *
 * Without its own counter these rows fall into `skipped_rows`, which reads as "nothing
 * happened" — the opposite of the truth. An own-match row records a new intervention on
 * the existing person; it is a delivery, not a discard. The completion notification is
 * the main place an officer learns how an async import went, so a wrong bucket there is
 * a wrong answer to the only question they asked.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_batches', function (Blueprint $table): void {
            $table->unsignedInteger('own_rows')->default(0)->after('served_rows');
        });
    }

    public function down(): void
    {
        Schema::table('import_batches', function (Blueprint $table): void {
            $table->dropColumn('own_rows');
        });
    }
};
