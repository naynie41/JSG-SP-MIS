<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Staged rows for a bulk import (PRD FR-REG-06). Each source row is validated and
 * stored here with its validation result BEFORE any commit; invalid rows are
 * retained (never silently dropped). On confirm, valid rows are committed and
 * `beneficiary_id` is stamped — so re-running a commit never double-inserts.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_rows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('import_batch_id');
            $table->unsignedInteger('row_number'); // 1-based source row (excl. header)
            $table->string('original_record_id')->nullable();
            $table->json('payload');          // normalised source values (PII at rest)
            $table->boolean('is_valid')->default(false);
            $table->json('errors')->nullable(); // [{field,message}, ...]
            $table->uuid('beneficiary_id')->nullable(); // set once committed
            $table->timestamps();

            $table->foreign('import_batch_id')->references('id')->on('import_batches')->cascadeOnDelete();
            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->nullOnDelete();

            $table->index('import_batch_id');
            $table->index('beneficiary_id');
        });

        // One staged row per (batch, row number) — makes re-parsing idempotent
        // (portable across pgsql + sqlite).
        DB::statement('CREATE UNIQUE INDEX import_rows_batch_row_unique ON import_rows (import_batch_id, row_number)');
    }

    public function down(): void
    {
        Schema::dropIfExists('import_rows');
    }
};
