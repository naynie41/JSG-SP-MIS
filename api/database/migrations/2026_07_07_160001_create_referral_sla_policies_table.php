<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Admin-editable SLA windows per referral status (PRD FR-REF-04/05): how long a
 * referral may sit in a given status (e.g. time to accept, time to complete)
 * before it is overdue. One row per status; absent row = no SLA for that status.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_sla_policies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('status')->unique(); // ReferralStatus value the window applies to
            $table->unsignedInteger('hours');   // window before overdue
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_sla_policies');
    }
};
