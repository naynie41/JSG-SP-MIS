<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Staged rows of a benefit-delivery import (PRD FR-BEN-01/02, §8.3). Each row
 * references an EXISTING beneficiary (resolved by id/NIN/BVN) plus the benefit
 * fields. `benefit_id` is stamped on commit, which makes commits idempotent per
 * row (re-running never double-records). Reached through its (scoped) batch.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('benefit_import_rows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('benefit_import_batch_id');
            $table->unsignedInteger('row_number');

            $table->boolean('is_valid')->default(false);
            $table->json('errors')->nullable();
            $table->boolean('eligibility_flagged')->default(false);

            $table->uuid('resolved_beneficiary_id')->nullable(); // matched existing beneficiary
            $table->json('payload'); // parsed benefit fields
            $table->uuid('benefit_id')->nullable(); // set on commit → idempotency stamp

            $table->timestamps();

            $table->foreign('benefit_import_batch_id')->references('id')->on('benefit_import_batches')->cascadeOnDelete();
            $table->foreign('resolved_beneficiary_id')->references('id')->on('beneficiaries')->nullOnDelete();
            $table->foreign('benefit_id')->references('id')->on('benefits')->nullOnDelete();

            $table->index('benefit_import_batch_id');
            $table->index(['benefit_import_batch_id', 'row_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('benefit_import_rows');
    }
};
