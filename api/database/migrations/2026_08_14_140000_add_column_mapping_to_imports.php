<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The Data Import & Mapping layer (CLAUDE.md §11, PRD v1.7).
 *
 * Column mappings for NIN, BVN, name and phone must be explicitly confirmed on EVERY
 * import. Until now the adapters resolved them silently from an alias table, so a column
 * called `national_id` holding a voter's card number became a NIN and the duplicate
 * cascade treated it as a definitive identity match.
 *
 * `mapping_confirmed_at` is the gate. `column_map` records what a human actually agreed
 * to — a canonical field mapped to a source header, or explicitly to NULL meaning "this
 * source does not carry it". The distinction matters: a key absent from the map is
 * UNCONFIRMED, a key present with null is CONFIRMED ABSENT.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_batches', function (Blueprint $table) {
            $table->json('detected_headers')->nullable();
            $table->json('column_map')->nullable();
            // Identifies the SHAPE of a source file, so a saved template is offered only
            // for a file with the same columns. A changed export → new signature → re-map.
            $table->string('source_signature', 64)->nullable();
            $table->timestamp('mapping_confirmed_at')->nullable();
            $table->uuid('mapping_confirmed_by')->nullable();

            $table->foreign('mapping_confirmed_by')->references('id')->on('users')->nullOnDelete();
            $table->index('source_signature');
        });

        Schema::create('import_mapping_templates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('owner_mda_id');
            $table->string('source');            // RegistrationSource
            $table->string('source_signature', 64);
            $table->string('name');
            $table->json('column_map');
            $table->uuid('created_by')->nullable();
            $table->timestamps();

            $table->foreign('owner_mda_id')->references('id')->on('mdas')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            // One template per (MDA, source, file shape) — a later confirmation for the
            // same shape updates it rather than accumulating near-duplicates.
            $table->unique(['owner_mda_id', 'source', 'source_signature']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_mapping_templates');

        Schema::table('import_batches', function (Blueprint $table) {
            $table->dropForeign(['mapping_confirmed_by']);
            $table->dropIndex(['source_signature']);
            $table->dropColumn([
                'detected_headers', 'column_map', 'source_signature',
                'mapping_confirmed_at', 'mapping_confirmed_by',
            ]);
        });
    }
};
