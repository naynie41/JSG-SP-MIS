<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Supporting documents attached to a beneficiary (PRD FR-REG-07). Files live
 * outside the web root on a private disk (see SECURITY.md §5); this table holds
 * only metadata + the stored path + a SHA-256 checksum for integrity. Access is
 * MDA-scoped (`owner_mda_id`, denormalised from the beneficiary) and audited.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beneficiary_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('beneficiary_id');
            $table->uuid('owner_mda_id'); // denormalised for MDA scoping
            $table->uuid('uploaded_by')->nullable();
            $table->string('document_type');
            $table->string('original_filename');
            $table->string('stored_path');
            $table->string('mime_type');
            $table->unsignedBigInteger('size_bytes');
            $table->string('checksum_sha256', 64);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->cascadeOnDelete();
            $table->foreign('owner_mda_id')->references('id')->on('mdas');
            $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();

            $table->index('beneficiary_id');
            $table->index('owner_mda_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiary_documents');
    }
};
