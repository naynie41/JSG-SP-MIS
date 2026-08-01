<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Funding-partner attribution on an activity (Phase 6P prerequisite, FR-RPT-02).
 *
 * Today a Development Partner is linked to a PROGRAMME (`programme_funders`); the
 * partner that funds a specific ACTIVITY — and therefore its budget, its delivered
 * value and the beneficiaries it reaches — is not queryable. `funding_partner_id`
 * makes that attribution explicit and scoped: a partner's "funded programmes" become
 * the distinct programmes of the activities they fund.
 *
 * Nullable (an activity may be state-funded / not partner-attributed). Set/nulled by
 * the owning MDA or a System Administrator on create/edit, validated to a Development
 * Partner user, and audited (Activity is Auditable). Beneficiary ownership + the
 * catalog/activity model are unchanged. `funding_source` (free text) is kept for
 * display/legacy.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->uuid('funding_partner_id')->nullable()->after('funding_source');
            $table->foreign('funding_partner_id')->references('id')->on('users')->nullOnDelete();
            $table->index('funding_partner_id');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['funding_partner_id']);
            $table->dropColumn('funding_partner_id');
        });
    }
};
