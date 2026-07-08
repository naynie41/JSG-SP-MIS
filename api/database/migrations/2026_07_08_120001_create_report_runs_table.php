<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Generated report runs (PRD FR-RPT-03). A run captures WHICH report + format a user
 * requested and the SCOPE resolved at request time, so the queued generation applies
 * exactly that scope. The output file is stored on the `local` disk; the row tracks
 * status through pending → processing → ready | failed. Personal to the requester.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_runs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('report_key');
            $table->string('report_label');
            $table->string('format'); // csv | xlsx | pdf
            $table->string('status')->default('pending');

            // Scope captured at request time (FR-DSH-01) so the job re-applies it.
            $table->string('scope_kind');
            $table->string('scope_label');
            $table->json('scope_mda_ids')->nullable();
            $table->json('scope_programme_ids')->nullable();
            $table->json('params')->nullable();

            $table->unsignedInteger('row_count')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->text('error')->nullable();

            $table->uuid('requested_by')->nullable();
            $table->uuid('requested_mda_id')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('requested_by')->references('id')->on('users')->nullOnDelete();

            $table->index('requested_by');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_runs');
    }
};
