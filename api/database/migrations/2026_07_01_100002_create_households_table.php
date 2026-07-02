<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Households — optional grouping of beneficiaries (PRD FR-REG-01, §9). Owned and
 * MDA-scoped via `owner_mda_id`; the head references a beneficiary. Membership
 * (with history) lives in household_memberships.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('households', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('owner_mda_id');
            $table->uuid('head_beneficiary_id')->nullable();

            // Provenance (FR-REG-03).
            $table->string('registration_source');
            $table->date('registration_date');
            $table->uuid('import_batch_id')->nullable();
            $table->string('original_record_id')->nullable();

            $table->string('address')->nullable();
            $table->string('lga')->nullable();
            $table->string('ward')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('owner_mda_id')->references('id')->on('mdas');
            $table->foreign('head_beneficiary_id')->references('id')->on('beneficiaries')->nullOnDelete();

            $table->index('owner_mda_id');
            $table->index('head_beneficiary_id');
            $table->index('import_batch_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};
