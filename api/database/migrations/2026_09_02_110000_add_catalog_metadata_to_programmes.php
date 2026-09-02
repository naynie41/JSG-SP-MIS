<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Catalog metadata carried by the MDA programme inventory (PRD §10).
 *
 * `target_group` is a free-text HINT, deliberately not a controlled vocabulary. The
 * source inventory holds 39 distinct values that do not yet form one: `PWDs` and
 * `PWDS` differ only in case, `Youth/Women` is two groups in one string, and there
 * are typos. Collapsing those is a stakeholder decision for SP Coordination, not a
 * migration — so the value is stored verbatim and surfaced for review. Eligibility
 * that the system ENFORCES stays in the structured `eligibility` column.
 *
 * `is_automated` records whether the MDA runs the programme through an automated
 * pipeline, as stated in the inventory. It is descriptive only: nothing keys off it.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programmes', function (Blueprint $table): void {
            $table->string('target_group')->nullable()->after('benefit_category');
            $table->boolean('is_automated')->default(false)->after('target_group');
        });
    }

    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table): void {
            $table->dropColumn(['target_group', 'is_automated']);
        });
    }
};
