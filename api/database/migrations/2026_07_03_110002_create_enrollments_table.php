<?php

declare(strict_types=1);

use App\Domain\Programme\Enums\EnrollmentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Enrollments — link an already-registered beneficiary (individual programme) or
 * household (household programme) to a programme/activity (PRD FR-PRG-03).
 *
 * `mda_id` is the ENROLLING MDA (the programme owner) and the scoping column — a
 * serving MDA can enroll a beneficiary owned by another MDA (via the serve seam)
 * without any ownership change, so this is deliberately independent of the
 * beneficiary's owner MDA. Exactly one of `beneficiary_id`/`household_id` is set,
 * matching the programme type (enforced in the service). Partial-unique indexes
 * keep a single OPEN enrollment per (programme, target).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('programme_id');
            $table->uuid('activity_id')->nullable();
            $table->uuid('mda_id'); // enrolling MDA (programme owner) — scope column

            $table->uuid('beneficiary_id')->nullable();
            $table->uuid('household_id')->nullable();

            $table->string('status')->default(EnrollmentStatus::Enrolled->value);
            $table->date('enrolled_on');
            $table->date('exited_on')->nullable();
            $table->string('exit_reason')->nullable();

            // Advisory eligibility outcome (FR-PRG-03): flagged when criteria unmet.
            $table->boolean('eligibility_flagged')->default(false);
            $table->json('eligibility_notes')->nullable();

            $table->uuid('enrolled_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('programme_id')->references('id')->on('programmes')->cascadeOnDelete();
            $table->foreign('activity_id')->references('id')->on('activities')->nullOnDelete();
            $table->foreign('mda_id')->references('id')->on('mdas');
            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->cascadeOnDelete();
            $table->foreign('household_id')->references('id')->on('households')->cascadeOnDelete();
            $table->foreign('enrolled_by')->references('id')->on('users')->nullOnDelete();

            $table->index('programme_id');
            $table->index('activity_id');
            $table->index('mda_id');
            $table->index('beneficiary_id');
            $table->index('household_id');
            $table->index(['programme_id', 'status']);
        });

        // One OPEN enrollment per (programme, target). Portable across pg + sqlite.
        DB::statement("CREATE UNIQUE INDEX enrollments_prog_beneficiary_open ON enrollments (programme_id, beneficiary_id) WHERE status = 'enrolled' AND beneficiary_id IS NOT NULL");
        DB::statement("CREATE UNIQUE INDEX enrollments_prog_household_open ON enrollments (programme_id, household_id) WHERE status = 'enrolled' AND household_id IS NOT NULL");
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
