<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Dashboard summary snapshots (PRD FR-RPT-01/02, FR-DSH-01). One row per resolved
 * dashboard scope (`state-wide`, a set of MDAs, or a partner's funded programmes),
 * holding the precomputed metric payload. The request path reads a single indexed
 * row here — it never scans the raw ledger/registry tables. Refreshed on a schedule
 * (and on demand) by the reporting snapshot service; the payload is de-identified
 * aggregate data only (no PII).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dashboard_snapshots', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('scope_key')->unique(); // stable key for the resolved scope
            $table->string('scope_kind');           // state_wide | mda | partner
            $table->string('scope_label')->nullable();
            $table->json('metrics');                // aggregate payload (no PII)
            $table->timestamp('computed_at');
            $table->timestamps();

            $table->index('scope_kind');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_snapshots');
    }
};
