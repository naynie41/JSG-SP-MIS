<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Which MDAs RUN a catalog programme (PRD §10, FR-PRG-02).
 *
 * Programmes stay GLOBAL and unowned — this records participation, never ownership.
 * The same catalog entry may be run by several MDAs, which is the whole point of a
 * shared catalog.
 *
 * Why an association rather than an Activity per pair: an Activity carries location,
 * schedule, budget, funding and period. Seeding 112 of them from an inventory that
 * holds none of those would invent every one of those fields, and §10 already settles
 * that argument — "a placeholder is a worse record than an honest absence". MDAs
 * create real activities as they actually plan them; this table says only that the
 * MDA runs the programme at all.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mda_programme', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('mda_id');
            $table->uuid('programme_id');
            // Where the association came from — a seeded inventory row is a weaker
            // claim than an MDA declaring it in the console, and the difference
            // should stay visible.
            $table->string('source')->default('inventory');
            $table->timestamps();

            $table->foreign('mda_id')->references('id')->on('mdas')->cascadeOnDelete();
            $table->foreign('programme_id')->references('id')->on('programmes')->cascadeOnDelete();
            // Idempotency is enforced by the DATABASE, not only by the seeder: re-running
            // cannot double-insert even if the seeder's own guard is wrong.
            $table->unique(['mda_id', 'programme_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mda_programme');
    }
};
