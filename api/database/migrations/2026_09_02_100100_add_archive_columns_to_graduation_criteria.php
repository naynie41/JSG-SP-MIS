<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Archive-as-delete for graduation criteria (PRD §10, FR-GRD-01).
 *
 * `graduation_events.criteria_id` records WHICH criteria set a person was graduated
 * against, and that FK is `nullOnDelete`. So the previous hard delete did not remove
 * any graduation event — it silently nulled the reference on every one of them,
 * erasing the answer to "why was this person graduated?" from the historical record
 * with no error and no trace.
 *
 * Criteria are now archived instead: the row survives, every graduation event keeps
 * pointing at it, and the set simply stops being offered for new graduations.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('graduation_criteria', function (Blueprint $table): void {
            $table->timestamp('archived_at')->nullable()->after('is_active');
            $table->uuid('archived_by')->nullable()->after('archived_at');

            $table->foreign('archived_by')->references('id')->on('users')->nullOnDelete();
            $table->index('archived_at');
        });
    }

    public function down(): void
    {
        Schema::table('graduation_criteria', function (Blueprint $table): void {
            $table->dropForeign(['archived_by']);
            $table->dropIndex(['archived_at']);
            $table->dropColumn(['archived_at', 'archived_by']);
        });
    }
};
