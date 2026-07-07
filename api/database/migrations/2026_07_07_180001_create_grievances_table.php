<?php

declare(strict_types=1);

use App\Domain\Grievance\Enums\GrievanceStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Grievances / GRM (PRD FR-GRM-01/02, §8.4). Logged by STAFF on behalf of a
 * beneficiary (no citizen self-service) and handled within one MDA
 * (`handling_mda_id`, MDA-scoped). Lifecycle: Open → Assigned → InProgress →
 * Resolved → Closed, with a timestamp per transition and resolution notes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grievances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('handling_mda_id');            // scope column (the owning MDA)
            $table->uuid('beneficiary_id')->nullable(); // optional link
            $table->string('category');
            $table->string('channel');
            $table->text('description');
            $table->string('status')->default(GrievanceStatus::Open->value);
            $table->uuid('assignee_user_id')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->uuid('submitted_by')->nullable();   // the staff member who logged it

            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->foreign('handling_mda_id')->references('id')->on('mdas');
            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->nullOnDelete();
            $table->foreign('assignee_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('submitted_by')->references('id')->on('users')->nullOnDelete();

            $table->index('handling_mda_id');
            $table->index('status');
            $table->index('assignee_user_id');
            $table->index('beneficiary_id');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grievances');
    }
};
