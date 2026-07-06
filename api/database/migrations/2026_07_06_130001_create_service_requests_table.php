<?php

declare(strict_types=1);

use App\Domain\Registry\Enums\ServiceRequestStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Service Request (PRD §12, FR-OWN-06): a non-owner MDA that matches a duplicate
 * asks the OWNER MDA for permission to serve an existing beneficiary. The owner
 * accepts or declines; ownership (`beneficiaries.owner_mda_id`) NEVER changes.
 * On acceptance a read-access grant is opened (see beneficiary_service_grants).
 *
 * Distinct from ownership_transfer_requests (which changes the owner) and from the
 * Referral flow (outbound). At most one pending request per (beneficiary, requester).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('beneficiary_id');
            $table->uuid('from_mda_id'); // requesting MDA
            $table->uuid('to_mda_id');   // owner MDA
            $table->string('status')->default(ServiceRequestStatus::Pending->value);
            $table->string('reason', 1000)->nullable();
            $table->uuid('import_row_id')->nullable();
            $table->uuid('requested_by')->nullable();
            $table->uuid('decided_by')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->string('decision_reason', 1000)->nullable();
            $table->timestamps();

            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->cascadeOnDelete();
            $table->foreign('from_mda_id')->references('id')->on('mdas');
            $table->foreign('to_mda_id')->references('id')->on('mdas');
            $table->foreign('import_row_id')->references('id')->on('import_rows')->nullOnDelete();
            $table->foreign('requested_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('decided_by')->references('id')->on('users')->nullOnDelete();

            $table->index('beneficiary_id');
            $table->index('from_mda_id');
            $table->index('to_mda_id');
            $table->index('status');
        });

        // One pending request per beneficiary per requesting MDA (portable).
        DB::statement("CREATE UNIQUE INDEX service_requests_one_pending ON service_requests (beneficiary_id, from_mda_id) WHERE status = 'pending'");
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
