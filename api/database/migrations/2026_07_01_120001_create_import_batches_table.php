<?php

declare(strict_types=1);

use App\Domain\Registry\Enums\ImportStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bulk import batches (PRD FR-REG-02/06): tracks an uploaded Excel/CSV file
 * through parse → preview → confirm → commit, with per-stage counts and the
 * uploading user/MDA. The raw rows live in `import_rows`; nothing reaches the
 * `beneficiaries` table until the preview is confirmed.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_batches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('owner_mda_id');   // MDA that will own committed records
            $table->uuid('uploaded_by')->nullable();
            $table->string('original_filename');
            $table->string('stored_path');
            $table->string('source'); // RegistrationSource: excel | csv
            $table->string('status')->default(ImportStatus::Pending->value);
            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('valid_rows')->default(0);
            $table->unsignedInteger('invalid_rows')->default(0);
            $table->unsignedInteger('committed_rows')->default(0);
            $table->text('error')->nullable(); // batch-level failure reason
            $table->timestamps();

            $table->foreign('owner_mda_id')->references('id')->on('mdas');
            $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();

            $table->index('owner_mda_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_batches');
    }
};
