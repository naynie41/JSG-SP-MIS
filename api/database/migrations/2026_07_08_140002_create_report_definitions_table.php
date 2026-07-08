<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Saved ad-hoc report definitions (PRD FR-RPT-03). A user composes a definition
 * (dataset + group-by + measures + filters) and saves it for reuse — and as the basis
 * for scheduling (6.6). The definition holds only whitelisted KEYS (no scope, no PII);
 * scope is resolved from the running user each time it is generated. Personal to owner.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_definitions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('dataset');
            $table->json('definition'); // { group_by, measures, filters }
            $table->uuid('owner_user_id')->nullable();
            $table->uuid('owner_mda_id')->nullable();
            $table->timestamps();

            $table->foreign('owner_user_id')->references('id')->on('users')->nullOnDelete();

            $table->index('owner_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_definitions');
    }
};
