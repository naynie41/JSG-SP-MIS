<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Scheduled reports (PRD FR-RPT-04). A schedule pairs a standard report (`report_key`)
 * OR a saved ad-hoc definition (`report_definition_id`) with a frequency, format,
 * delivery mode and a validated recipient list. The caller's SCOPE is captured here so
 * the unattended run generates exactly what the owner was entitled to see; recipients
 * are validated to cover that scope so a schedule can never deliver out-of-scope data.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');

            // Exactly one of these identifies WHAT is generated.
            $table->string('report_key')->nullable();
            $table->uuid('report_definition_id')->nullable();

            $table->string('format');                 // csv | xlsx | pdf
            $table->string('frequency');              // daily | weekly | monthly
            $table->string('delivery')->default('link'); // link | attachment
            $table->string('status')->default('active'); // active | paused

            // Captured scope (as on report_runs) + validated recipients.
            $table->string('scope_kind');
            $table->string('scope_label');
            $table->json('scope_mda_ids')->nullable();
            $table->json('scope_programme_ids')->nullable();
            $table->json('recipient_user_ids');

            $table->uuid('owner_user_id')->nullable();
            $table->uuid('owner_mda_id')->nullable();
            $table->date('last_run_on')->nullable();
            $table->timestamps();

            $table->foreign('report_definition_id')->references('id')->on('report_definitions')->nullOnDelete();
            $table->foreign('owner_user_id')->references('id')->on('users')->nullOnDelete();

            $table->index('owner_user_id');
            $table->index(['status', 'frequency']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_schedules');
    }
};
