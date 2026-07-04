<?php

declare(strict_types=1);

use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Enums\VerificationMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The benefit ledger — a complete history of benefits DELIVERED to beneficiaries
 * (PRD FR-BEN-01/02, §8.3). This records delivery only; it does NOT move money
 * (§2.3) — `monetary_value` is descriptive data (integer minor units, kobo, NGN).
 *
 * `mda_id` is the DELIVERING MDA (the scoping column) and is independent of the
 * beneficiary's owner MDA: a serving MDA may deliver to a beneficiary it does not
 * own. The per-beneficiary ledger is read across all MDAs (subject to visibility).
 * Append-mostly: corrections use the `reversed` status, not deletion.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('benefits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('beneficiary_id');
            $table->uuid('programme_id');
            $table->uuid('activity_id')->nullable();
            $table->uuid('enrollment_id')->nullable();
            $table->uuid('mda_id'); // delivering MDA — scope column

            $table->string('benefit_type');
            $table->decimal('quantity', 12, 3)->nullable();
            $table->string('unit')->nullable();
            $table->unsignedBigInteger('monetary_value')->nullable(); // minor units (kobo), data only
            $table->string('funding_source')->nullable();
            $table->date('delivery_date');
            $table->string('status')->default(BenefitStatus::Recorded->value);

            // Verification (FR-BEN-04).
            $table->string('verification_method')->default(VerificationMethod::None->value);
            $table->string('verification_reference')->nullable();
            $table->uuid('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->uuid('recorded_by')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->cascadeOnDelete();
            $table->foreign('programme_id')->references('id')->on('programmes')->cascadeOnDelete();
            $table->foreign('activity_id')->references('id')->on('activities')->nullOnDelete();
            $table->foreign('enrollment_id')->references('id')->on('enrollments')->nullOnDelete();
            $table->foreign('mda_id')->references('id')->on('mdas');
            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('recorded_by')->references('id')->on('users')->nullOnDelete();

            $table->index('mda_id');
            $table->index('programme_id');
            $table->index('activity_id');
            $table->index('delivery_date');
            $table->index(['beneficiary_id', 'delivery_date']);
            // Supports the per-beneficiary, per-type window scan (double-dipping, next step).
            $table->index(['beneficiary_id', 'benefit_type', 'delivery_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('benefits');
    }
};
