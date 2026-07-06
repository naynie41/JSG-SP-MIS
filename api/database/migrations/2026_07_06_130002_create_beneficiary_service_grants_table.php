<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Explicit read-access grant (PRD §12, FR-OWN-07): opened when the owner MDA
 * ACCEPTS a Service Request. It gives the requesting MDA (`mda_id`) READ-only
 * access to the full beneficiary record — nothing more; ownership and edit rights
 * never move. A grant may later be revoked (`revoked_at`); an active grant has a
 * null `revoked_at`. At most one active grant per (beneficiary, MDA).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beneficiary_service_grants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('beneficiary_id');
            $table->uuid('mda_id');            // the granted (requesting) MDA
            $table->uuid('service_request_id'); // the request that opened it
            $table->uuid('granted_by')->nullable();
            $table->timestamp('granted_at');
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->cascadeOnDelete();
            $table->foreign('mda_id')->references('id')->on('mdas')->cascadeOnDelete();
            $table->foreign('service_request_id')->references('id')->on('service_requests')->cascadeOnDelete();
            $table->foreign('granted_by')->references('id')->on('users')->nullOnDelete();

            $table->index('beneficiary_id');
            $table->index('mda_id');
        });

        // One active (un-revoked) grant per beneficiary per MDA (portable).
        DB::statement('CREATE UNIQUE INDEX beneficiary_service_grants_one_active ON beneficiary_service_grants (beneficiary_id, mda_id) WHERE revoked_at IS NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiary_service_grants');
    }
};
