<?php

declare(strict_types=1);

use App\Domain\Registry\Enums\ServeRequestStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Request-to-serve (PRD FR-DUP-05): a requesting MDA asks the owner MDA to serve
 * an existing beneficiary WITHOUT taking ownership. Distinct from
 * ownership_transfer_requests (which changes the owner). At most one pending
 * request per (beneficiary, requesting MDA).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('serve_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('beneficiary_id');
            $table->uuid('from_mda_id'); // requesting MDA
            $table->uuid('to_mda_id');   // owner MDA
            $table->string('status')->default(ServeRequestStatus::Pending->value);
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
        DB::statement("CREATE UNIQUE INDEX serve_requests_one_pending ON serve_requests (beneficiary_id, from_mda_id) WHERE status = 'pending'");
    }

    public function down(): void
    {
        Schema::dropIfExists('serve_requests');
    }
};
