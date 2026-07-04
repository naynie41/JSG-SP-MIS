<?php

declare(strict_types=1);

use App\Domain\Benefit\Enums\BenefitFlagStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Potential double-dipping flags (PRD FR-BEN-05): raised when the same benefit type
 * is delivered to the same beneficiary by DIFFERENT MDAs within a rule's window.
 * Surfaced for review — never blocks delivery. Involves two MDAs, so visibility is
 * decided per query (either delivering MDA, the beneficiary owner, or oversight).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('benefit_flags', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('beneficiary_id');
            $table->uuid('benefit_id');          // the newer delivery that triggered the flag
            $table->uuid('related_benefit_id');  // the earlier conflicting delivery
            $table->uuid('rule_id')->nullable();
            $table->string('benefit_type');
            $table->uuid('from_mda_id');   // delivering MDA of the triggering benefit
            $table->uuid('other_mda_id');  // delivering MDA of the conflicting benefit
            $table->string('status')->default(BenefitFlagStatus::Open->value);
            $table->string('reason');
            $table->uuid('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->string('review_note')->nullable();
            $table->timestamps();

            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->cascadeOnDelete();
            $table->foreign('benefit_id')->references('id')->on('benefits')->cascadeOnDelete();
            $table->foreign('related_benefit_id')->references('id')->on('benefits')->cascadeOnDelete();
            $table->foreign('rule_id')->references('id')->on('double_dipping_rules')->nullOnDelete();
            $table->foreign('from_mda_id')->references('id')->on('mdas');
            $table->foreign('other_mda_id')->references('id')->on('mdas');
            $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();

            $table->index('beneficiary_id');
            $table->index('status');
            $table->index('from_mda_id');
            $table->index('other_mda_id');
            // One flag per directed (new, conflicting) pair.
            $table->unique(['benefit_id', 'related_benefit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('benefit_flags');
    }
};
