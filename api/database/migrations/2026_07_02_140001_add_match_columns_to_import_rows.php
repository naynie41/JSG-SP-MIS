<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Duplicate-verification results on each staged import row (PRD FR-DUP-01/04,
 * §8.1). The parse/preview step annotates every row with its outcome band and the
 * matched candidates (existing beneficiaries and/or earlier rows in the batch).
 * `resolution` carries the user's decision, filled in the next step (3.5).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_rows', function (Blueprint $table) {
            $table->string('match_band')->nullable()->after('errors');       // exact | probable | none
            $table->json('match_candidates')->nullable()->after('match_band');
            $table->string('resolution')->nullable()->after('match_candidates'); // set in 3.5
        });
    }

    public function down(): void
    {
        Schema::table('import_rows', function (Blueprint $table) {
            $table->dropColumn(['match_band', 'match_candidates', 'resolution']);
        });
    }
};
