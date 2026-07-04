<?php

declare(strict_types=1);

use App\Domain\Registry\Enums\ImportStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bulk benefit-delivery import batches (PRD FR-BEN-01/02, §8.3) — a distribution
 * list keyed to an activity. Reuses the Phase 2 import lifecycle (upload → preview
 * → confirm → commit). This creates BENEFITS in bulk, not beneficiaries; the
 * delivering MDA is the importer (`mda_id`), which owns the activity/programme.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('benefit_import_batches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('mda_id');       // importing = delivering MDA (scope column)
            $table->uuid('activity_id');  // the delivery list is keyed to an activity
            $table->uuid('programme_id'); // denormalised from the activity
            $table->uuid('uploaded_by')->nullable();

            $table->string('original_filename');
            $table->string('stored_path');
            $table->string('source'); // csv | excel (for display)
            $table->string('status')->default(ImportStatus::Pending->value);

            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('valid_rows')->default(0);
            $table->unsignedInteger('invalid_rows')->default(0);
            $table->unsignedInteger('committed_rows')->default(0);
            $table->text('error')->nullable();

            $table->timestamps();

            $table->foreign('mda_id')->references('id')->on('mdas');
            $table->foreign('activity_id')->references('id')->on('activities')->cascadeOnDelete();
            $table->foreign('programme_id')->references('id')->on('programmes')->cascadeOnDelete();
            $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();

            $table->index('mda_id');
            $table->index('activity_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('benefit_import_batches');
    }
};
