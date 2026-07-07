<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SLA tracking on referrals (PRD FR-REF-04/05): the escalation level reached and
 * when the current status first breached its SLA. The scheduled job flags/escalates
 * — it NEVER changes the referral status (no auto-close).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->unsignedInteger('escalation_level')->default(0)->after('status');
            $table->timestamp('sla_breached_at')->nullable()->after('closed_at');
        });
    }

    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropColumn(['escalation_level', 'sla_breached_at']);
        });
    }
};
