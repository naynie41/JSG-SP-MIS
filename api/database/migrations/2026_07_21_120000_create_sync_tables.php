<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Data synchronization (FR-DSH-02, FR-REG-08). A `sync_connector` is a configured
 * external/source system (SOCU, a government system) owned by an MDA; a `sync_run`
 * is one scheduled/triggered/offline execution; `sync_run_rows` is the append-only,
 * per-record outcome log surfaced to admins. Credentials live in config/env, never
 * here (a connector only references a config key).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sync_connectors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('source');                 // RegistrationSource value (socu, government_system, ...)
            $table->uuid('owner_mda_id');             // records synced through this connector are owned here
            $table->string('conflict_policy')->default('flag_for_review');
            $table->string('credentials_ref')->nullable(); // key into config('sync.connections')
            $table->boolean('enabled')->default(true);
            $table->string('schedule')->nullable();   // human label of cadence (the scheduler tick drives it)
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();

            $table->foreign('owner_mda_id')->references('id')->on('mdas');
            $table->index(['enabled', 'source']);
        });

        Schema::create('sync_runs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('connector_id')->nullable(); // null for offline batches
            $table->string('trigger');                // scheduled | manual | offline_batch
            $table->string('source');
            $table->uuid('owner_mda_id');
            $table->string('conflict_policy');
            $table->string('status')->default('pending');
            $table->unsignedInteger('fetched_count')->default(0);
            $table->unsignedInteger('created_count')->default(0);
            $table->unsignedInteger('updated_count')->default(0);
            $table->unsignedInteger('skipped_count')->default(0);
            $table->unsignedInteger('flagged_count')->default(0);
            $table->unsignedInteger('rejected_count')->default(0);
            $table->unsignedInteger('error_count')->default(0);
            $table->text('error')->nullable();
            $table->uuid('triggered_by')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();

            $table->foreign('connector_id')->references('id')->on('sync_connectors')->nullOnDelete();
            $table->foreign('owner_mda_id')->references('id')->on('mdas');
            $table->foreign('triggered_by')->references('id')->on('users')->nullOnDelete();
            $table->index(['connector_id', 'created_at']);
            $table->index('status');
        });

        Schema::create('sync_run_rows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('sync_run_id');
            $table->string('original_record_id')->nullable();
            $table->string('outcome');
            $table->uuid('beneficiary_id')->nullable();
            $table->string('match_band')->nullable();
            $table->json('detail')->nullable();       // errors / conflict reason (never raw PII beyond the source id)
            $table->timestamp('created_at')->useCurrent(); // append-only

            $table->foreign('sync_run_id')->references('id')->on('sync_runs')->cascadeOnDelete();
            $table->index(['sync_run_id', 'outcome']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_run_rows');
        Schema::dropIfExists('sync_runs');
        Schema::dropIfExists('sync_connectors');
    }
};
