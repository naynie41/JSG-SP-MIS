<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Duplicate-decision details on a staged import row (PRD FR-DUP-05/06). The
 * `resolution` column already exists; this records the justification, which
 * existing beneficiary was linked/served, and who resolved it. Batch counters for
 * served/skipped rows are added too.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_rows', function (Blueprint $table) {
            $table->string('resolution_note', 1000)->nullable()->after('resolution');
            $table->uuid('resolved_beneficiary_id')->nullable()->after('resolution_note');
            $table->uuid('resolved_by')->nullable()->after('resolved_beneficiary_id');
            $table->timestamp('resolved_at')->nullable()->after('resolved_by');

            $table->foreign('resolved_beneficiary_id')->references('id')->on('beneficiaries')->nullOnDelete();
            $table->foreign('resolved_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('import_batches', function (Blueprint $table) {
            $table->unsignedInteger('served_rows')->default(0)->after('committed_rows');
            $table->unsignedInteger('skipped_rows')->default(0)->after('served_rows');
        });
    }

    public function down(): void
    {
        Schema::table('import_rows', function (Blueprint $table) {
            $table->dropForeign(['resolved_beneficiary_id']);
            $table->dropForeign(['resolved_by']);
            $table->dropColumn(['resolution_note', 'resolved_beneficiary_id', 'resolved_by', 'resolved_at']);
        });

        Schema::table('import_batches', function (Blueprint $table) {
            $table->dropColumn(['served_rows', 'skipped_rows']);
        });
    }
};
