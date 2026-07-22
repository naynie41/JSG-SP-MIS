<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Retention lifecycle markers on the beneficiary (NFR-PRV-01). These record what the
 * retention engine did to a record without destroying it:
 *   - `retention_flagged_at` : a `flag` policy marked it for DPO review.
 *   - `anonymized_at`        : an `aggregate`/`anonymize` policy de-identified it.
 *   - `retention_policy`     : the key of the policy that last acted (traceability).
 * The row, its operational history, and its audit trail always remain.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->timestamp('retention_flagged_at')->nullable()->after('status');
            $table->timestamp('anonymized_at')->nullable()->after('retention_flagged_at');
            $table->string('retention_policy')->nullable()->after('anonymized_at');

            $table->index('anonymized_at');
            $table->index('retention_flagged_at');
        });
    }

    public function down(): void
    {
        Schema::table('beneficiaries', function (Blueprint $table) {
            $table->dropIndex(['anonymized_at']);
            $table->dropIndex(['retention_flagged_at']);
            $table->dropColumn(['retention_flagged_at', 'anonymized_at', 'retention_policy']);
        });
    }
};
