<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Links a Development Partner user to the programmes they fund/monitor (PRD §4,
 * FR-RPT-02). This is what makes "funded programmes only" a concrete, queryable
 * scope for partner dashboards — a partner sees aggregates for their funded
 * programmes (which may span MDAs), never the wider registry. Read-only association;
 * managed by admins. One row per (programme, partner user).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programme_funders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('programme_id');
            $table->uuid('user_id'); // the Development Partner user
            $table->timestamps();

            $table->foreign('programme_id')->references('id')->on('programmes')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();

            $table->unique(['programme_id', 'user_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programme_funders');
    }
};
