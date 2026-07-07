<?php

declare(strict_types=1);

use App\Domain\Referral\Enums\ReferralStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Referrals (PRD FR-REF-01/02/04, §8.2): an originating MDA refers a beneficiary to
 * another MDA for an identified need. Two-party — both `from_mda_id` and
 * `to_mda_id` can see it. The lifecycle is Created → Accepted / Rejected /
 * MoreInfoRequested → InProgress → Completed/Closed, with a timestamp per state.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('beneficiary_id');
            $table->uuid('from_mda_id'); // originating MDA
            $table->uuid('to_mda_id');   // receiving MDA
            $table->string('need');
            $table->text('notes')->nullable();
            $table->string('status')->default(ReferralStatus::Created->value);
            $table->text('outcome')->nullable();
            $table->string('reason', 1000)->nullable();        // rejection reason
            $table->string('info_request', 1000)->nullable();  // receiving MDA's question
            $table->string('info_response', 1000)->nullable(); // originating MDA's answer
            $table->uuid('created_by')->nullable();

            // Lifecycle timestamps (FR-REF-04): one per transition.
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('info_requested_at')->nullable();
            $table->timestamp('info_responded_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->cascadeOnDelete();
            $table->foreign('from_mda_id')->references('id')->on('mdas');
            $table->foreign('to_mda_id')->references('id')->on('mdas');
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            $table->index('beneficiary_id');
            $table->index('from_mda_id');
            $table->index('to_mda_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
