<?php

declare(strict_types=1);

use App\Domain\Programme\Enums\ProgrammeStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Programmes — a social-protection programme run by an MDA (PRD FR-PRG-01, §9).
 *
 * `owner_mda_id` is the ownership + MDA-scoping column (only the owner MDA may
 * mutate). `eligibility` holds structured/configurable criteria (JSON) used later
 * by programme-matching (FR-OWN-04). Monetary amounts are stored as integer minor
 * units (kobo, NGN) to stay exact. Soft-deletable; the exposed lifecycle uses the
 * `archived` status rather than deletion.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programmes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('owner_mda_id');

            $table->string('name');
            $table->text('objective')->nullable();
            $table->string('type'); // ProgrammeType: household | individual
            $table->json('eligibility')->nullable(); // structured criteria (FR-OWN-04)
            $table->string('funding_source')->nullable();
            $table->unsignedBigInteger('budget_amount')->nullable(); // minor units (kobo)
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->string('status')->default(ProgrammeStatus::Draft->value);

            $table->uuid('created_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('owner_mda_id')->references('id')->on('mdas');
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            $table->index('owner_mda_id');
            $table->index('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
