<?php

declare(strict_types=1);

use App\Domain\Matching\Enums\ExactMatchBehaviour;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Configurable duplicate-matching rules (PRD FR-DUP-02/03). Each row is an
 * immutable VERSION of the config; publishing a change inserts a new version and
 * flips `is_active`, so the full history is traceable and every change is audited.
 * At most one active version (partial unique).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matching_configs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('version');
            $table->boolean('is_active')->default(false);

            // Deterministic exact-match key sets, e.g. [["nin"], ["bvn"]].
            $table->json('deterministic_rules');
            // Weighted fuzzy fields: [{ field, comparator, weight }, ...].
            $table->json('fuzzy_fields');

            $table->decimal('review_threshold', 4, 3);
            $table->decimal('auto_accept_threshold', 4, 3)->nullable();
            $table->string('exact_match_behaviour')->default(ExactMatchBehaviour::Confirm->value);

            $table->string('description')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->unique('version');
        });

        // At most one active version (portable across pgsql + sqlite).
        DB::statement('CREATE UNIQUE INDEX matching_configs_single_active ON matching_configs (is_active) WHERE is_active');
    }

    public function down(): void
    {
        Schema::dropIfExists('matching_configs');
    }
};
