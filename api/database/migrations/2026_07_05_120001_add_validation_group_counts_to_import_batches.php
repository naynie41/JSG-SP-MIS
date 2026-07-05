<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Batch counters for the two validation groups (PRD §9, FR-REG-05/09): rows
 * rejected on a malformed identity field (never persisted) vs rows kept with one
 * or more non-identity fields dropped/flagged. Surfaced distinctly in the preview.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_batches', function (Blueprint $table) {
            $table->unsignedInteger('rejected_rows')->default(0)->after('invalid_rows');
            $table->unsignedInteger('dropped_field_rows')->default(0)->after('rejected_rows');
        });
    }

    public function down(): void
    {
        Schema::table('import_batches', function (Blueprint $table) {
            $table->dropColumn(['rejected_rows', 'dropped_field_rows']);
        });
    }
};
