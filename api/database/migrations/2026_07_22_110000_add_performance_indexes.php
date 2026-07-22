<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Hot-path composite indexes (NFR-PERF-01, NFR-SCAL-01). The single-column indexes
 * from Phase 2–4 cover point lookups; these composites cover the scoped-list ORDER BY
 * and the multi-column filters that the request path actually issues, so those queries
 * stay index-only as row counts grow:
 *
 *  - beneficiaries: owner-scoped list, newest registration first.
 *  - benefits: delivering-MDA ledger filtered by status / ordered by delivery date.
 *  - enrollments: per-beneficiary status lookups (graduation/exit, benefit recording).
 *  - beneficiary_consents: latest consent for a (beneficiary, purpose) — the gates.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->index(['owner_mda_id', 'registration_date'], 'beneficiaries_owner_reg_date_idx');
        });

        Schema::table('benefits', function (Blueprint $table) {
            $table->index(['mda_id', 'status'], 'benefits_mda_status_idx');
            $table->index(['mda_id', 'delivery_date'], 'benefits_mda_delivery_idx');
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->index(['beneficiary_id', 'status'], 'enrollments_beneficiary_status_idx');
        });

        Schema::table('beneficiary_consents', function (Blueprint $table) {
            $table->index(['beneficiary_id', 'purpose', 'created_at'], 'consents_beneficiary_purpose_idx');
        });
    }

    public function down(): void
    {
        Schema::table('beneficiaries', fn (Blueprint $table) => $table->dropIndex('beneficiaries_owner_reg_date_idx'));
        Schema::table('benefits', function (Blueprint $table) {
            $table->dropIndex('benefits_mda_status_idx');
            $table->dropIndex('benefits_mda_delivery_idx');
        });
        Schema::table('enrollments', fn (Blueprint $table) => $table->dropIndex('enrollments_beneficiary_status_idx'));
        Schema::table('beneficiary_consents', fn (Blueprint $table) => $table->dropIndex('consents_beneficiary_purpose_idx'));
    }
};
