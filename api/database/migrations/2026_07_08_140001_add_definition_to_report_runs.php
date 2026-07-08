<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ad-hoc report support (PRD FR-RPT-03). A run may carry an ad-hoc `definition`
 * (dataset + group-by + measures + filters) instead of a standard `report_key`; the
 * generation job builds from whichever is present. The scope columns already on the
 * run still capture the caller's scope at request time.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report_runs', function (Blueprint $table) {
            $table->json('definition')->nullable()->after('params');
        });
    }

    public function down(): void
    {
        Schema::table('report_runs', function (Blueprint $table) {
            $table->dropColumn('definition');
        });
    }
};
