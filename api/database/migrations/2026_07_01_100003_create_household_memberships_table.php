<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Household membership with history (PRD §9). A membership is "open" while
 * left_at IS NULL. The partial unique index enforces at most ONE open membership
 * per beneficiary at the database level — moves/splits must close the current
 * membership before opening a new one.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('household_memberships', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('household_id');
            $table->uuid('beneficiary_id');
            $table->string('role_in_household');
            $table->timestamp('joined_at');
            $table->timestamp('left_at')->nullable();
            $table->timestamps();

            $table->foreign('household_id')->references('id')->on('households')->cascadeOnDelete();
            $table->foreign('beneficiary_id')->references('id')->on('beneficiaries')->cascadeOnDelete();

            $table->index('household_id');
            $table->index('beneficiary_id');
        });

        // At most one OPEN membership per beneficiary (portable pgsql + sqlite).
        DB::statement('CREATE UNIQUE INDEX household_memberships_one_open_per_beneficiary ON household_memberships (beneficiary_id) WHERE left_at IS NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('household_memberships');
    }
};
