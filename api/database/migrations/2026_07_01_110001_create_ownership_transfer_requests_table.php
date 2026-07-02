<?php

declare(strict_types=1);

use App\Domain\Registry\Enums\OwnershipTransferStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ownership-transfer requests (PRD FR-OWN-05): a non-owner MDA requests
 * ownership; the current owner approves/rejects. All transitions are audited.
 * At most one pending request per beneficiary (partial unique).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ownership_transfer_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('beneficiary_id');
            $table->uuid('from_mda_id'); // owner at time of request
            $table->uuid('to_mda_id');   // requesting MDA
            $table->string('status')->default(OwnershipTransferStatus::Pending->value);
            $table->string('reason')->nullable();
            $table->uuid('requested_by')->nullable();
            $table->uuid('decided_by')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->string('decision_reason')->nullable();
            $table->timestamps();

            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->cascadeOnDelete();
            $table->foreign('from_mda_id')->references('id')->on('mdas');
            $table->foreign('to_mda_id')->references('id')->on('mdas');
            $table->foreign('requested_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('decided_by')->references('id')->on('users')->nullOnDelete();

            $table->index('beneficiary_id');
            $table->index('to_mda_id');
            $table->index('status');
        });

        // One pending transfer per beneficiary (portable pgsql + sqlite).
        DB::statement("CREATE UNIQUE INDEX ownership_transfers_one_pending_per_beneficiary ON ownership_transfer_requests (beneficiary_id) WHERE status = 'pending'");
    }

    public function down(): void
    {
        Schema::dropIfExists('ownership_transfer_requests');
    }
};
