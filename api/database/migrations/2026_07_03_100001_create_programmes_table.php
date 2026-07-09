<?php

declare(strict_types=1);

use App\Domain\Programme\Enums\ProgrammeStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Programmes — a GLOBAL catalog of social-protection programme *types* (PRD §10,
 * ARCH §12.4, revises FR-PRG-01). NOT owned or scoped by any MDA: created only by
 * the System Administrator (optionally SP Coordination) and readable by every MDA
 * so they can run it through their own Activities. It holds type-level attributes
 * only — name, objective, type (HH/individual), benefit category and standard
 * eligibility. Budget, funding and period live on the Activity, not here.
 * `eligibility` holds structured/configurable criteria (JSON) used by
 * programme-matching (FR-OWN-04). Soft-deletable; the exposed lifecycle uses the
 * `archived` status rather than deletion.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programmes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->text('objective')->nullable();
            $table->string('type'); // ProgrammeType: household | individual
            $table->string('benefit_category')->nullable(); // type-level benefit category (§10)
            $table->json('eligibility')->nullable(); // standard structured criteria (FR-OWN-04)
            $table->string('status')->default(ProgrammeStatus::Draft->value);

            $table->uuid('created_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            $table->index('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
