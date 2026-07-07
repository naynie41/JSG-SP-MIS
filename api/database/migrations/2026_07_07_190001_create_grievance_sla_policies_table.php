<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Admin-editable SLA windows per grievance category (PRD FR-GRM-03): how many hours
 * a grievance of a given category may remain unresolved (measured from when it was
 * logged) before it is overdue. One row per category; absent row = no SLA.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grievance_sla_policies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('category')->unique(); // GrievanceCategory value the window applies to
            $table->unsignedInteger('hours');     // window before overdue
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grievance_sla_policies');
    }
};
