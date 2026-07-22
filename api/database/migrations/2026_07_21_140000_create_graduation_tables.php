<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Graduation management (FR-GRD-01/02). `graduation_criteria` is an MDA-owned,
 * admin-editable rule set defined per catalog programme (the global programme is never
 * modified — §10). `graduation_events` is the permanent record of a graduation; it
 * changes the enrolment status but NEVER deletes the beneficiary or their ledger.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('graduation_criteria', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('programme_id');            // the catalog programme graduated from
            $table->uuid('owner_mda_id');            // the MDA that defined these criteria
            $table->string('name');
            $table->string('logic')->default('all'); // all | any
            $table->json('rules');                   // [{type, threshold}] — configurable, not hard-coded
            $table->boolean('is_active')->default(true);
            $table->uuid('created_by')->nullable();
            $table->timestamps();

            $table->foreign('programme_id')->references('id')->on('programmes')->cascadeOnDelete();
            $table->foreign('owner_mda_id')->references('id')->on('mdas');
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->index(['programme_id', 'owner_mda_id', 'is_active']);
        });

        Schema::create('graduation_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('enrollment_id');
            $table->uuid('beneficiary_id')->nullable();
            $table->uuid('household_id')->nullable();
            $table->uuid('programme_id');
            $table->uuid('activity_id')->nullable();
            $table->uuid('mda_id');                  // the graduating (enrolling) MDA
            $table->uuid('criteria_id')->nullable(); // the criteria set used, if any
            $table->text('reason')->nullable();
            $table->uuid('decided_by')->nullable();
            $table->timestamp('graduated_at')->useCurrent();
            $table->timestamps();

            $table->foreign('enrollment_id')->references('id')->on('enrollments')->cascadeOnDelete();
            $table->foreign('programme_id')->references('id')->on('programmes');
            $table->foreign('mda_id')->references('id')->on('mdas');
            $table->foreign('criteria_id')->references('id')->on('graduation_criteria')->nullOnDelete();
            $table->foreign('decided_by')->references('id')->on('users')->nullOnDelete();
            $table->index(['beneficiary_id', 'programme_id']);
            $table->index('mda_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('graduation_events');
        Schema::dropIfExists('graduation_criteria');
    }
};
