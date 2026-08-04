<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Capture the GOVERNANCE axis of the scope a report run/schedule was created under
 * (see DashboardScope::$governance). Without it, a run rehydrated by the queue or the
 * scheduler would lose the fact that it draws on an administrative dataset, and the
 * recipient check (DashboardScope::covers) could deliver an audit or user report to a
 * state-wide Executive. Defaults to false, so every existing row stays non-governance.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report_runs', function (Blueprint $table): void {
            $table->boolean('scope_governance')->default(false)->after('scope_label');
        });

        Schema::table('report_schedules', function (Blueprint $table): void {
            $table->boolean('scope_governance')->default(false)->after('scope_label');
        });
    }

    public function down(): void
    {
        Schema::table('report_runs', function (Blueprint $table): void {
            $table->dropColumn('scope_governance');
        });

        Schema::table('report_schedules', function (Blueprint $table): void {
            $table->dropColumn('scope_governance');
        });
    }
};
