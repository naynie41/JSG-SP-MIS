<?php

declare(strict_types=1);

use App\Domain\Programme\Enums\ActivityStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Activities — an MDA-owned unit of work that runs a catalog programme (PRD §10,
 * ARCH §12.4, FR-PRG-02). `owner_mda_id` is the CREATING MDA (its own ownership +
 * MdaScope column), so one global programme can be run by many MDAs, each through
 * its own activity. The MDA-specific execution details — budget, funding source,
 * schedule and period — live here, not on the programme. Location is captured as
 * LGA/Ward + a free description now; a PostGIS point column (`geom`) is added on
 * PostgreSQL for later GIS work (Phase 6) — skipped on sqlite so the test database
 * stays portable. Money is integer minor units (kobo, NGN).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('programme_id'); // the catalog programme this activity runs
            $table->uuid('owner_mda_id'); // the CREATING MDA (own scope, not the programme's)

            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('target_count')->nullable(); // target beneficiaries

            // Location (PostGIS-ready — see the pgsql-only geometry column below).
            $table->string('lga')->nullable();
            $table->string('ward')->nullable();
            $table->string('location_description')->nullable();

            $table->json('schedule')->nullable();
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->unsignedBigInteger('budget_amount')->nullable(); // minor units (kobo)
            $table->string('funding_source')->nullable(); // MDA-specific (moved from programme, §10)
            $table->string('status')->default(ActivityStatus::Draft->value);

            $table->uuid('created_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('programme_id')->references('id')->on('programmes')->cascadeOnDelete();
            $table->foreign('owner_mda_id')->references('id')->on('mdas');
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            $table->index('programme_id');
            $table->index('owner_mda_id');
            $table->index('status');
            $table->index(['programme_id', 'status']);
        });

        // PostGIS-ready location point — PostgreSQL only (Phase 6 GIS populates it).
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE activities ADD COLUMN geom geometry(Point, 4326)');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
