<?php

declare(strict_types=1);

use App\Domain\Programme\Enums\ActivityStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Activities — a unit of work under a programme (PRD FR-PRG-02, §9).
 *
 * `owner_mda_id` is denormalised from the parent programme so the same MdaScope
 * applies directly (an activity is scoped to the programme owner). Location is
 * captured as LGA/Ward + a free description now; a PostGIS point column (`geom`)
 * is added on PostgreSQL for later GIS work (Phase 6) — skipped on sqlite so the
 * test database stays portable. Money is integer minor units (kobo, NGN).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('programme_id');
            $table->uuid('owner_mda_id'); // denormalised from programme for scoping

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
