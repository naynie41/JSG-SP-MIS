<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Whether a programme's eligibility criteria are ENFORCED at enrollment
 * (PRD FR-PRG-03). Default false: the check is advisory (enrollment is flagged,
 * not blocked); when true, ineligible targets are rejected.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            $table->boolean('enforce_eligibility')->default(false)->after('eligibility');
        });
    }

    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            $table->dropColumn('enforce_eligibility');
        });
    }
};
