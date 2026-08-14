<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Persists that a connector's standing mapping has gone STALE (CLAUDE.md §11).
 *
 * The signature mismatch was already detected per run, but only in flight: each run
 * re-discovered it, and nothing recorded the state. That left the condition invisible
 * between runs — an administrator had no way to see that a connector had stopped, or
 * why, without reading run logs.
 *
 * Recording it turns "confirm once" into something that can actually be re-opened: the
 * connector is flagged in the UI, its next sync is held without even contacting the
 * source, and re-confirming clears the flag.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sync_connectors', function (Blueprint $table) {
            $table->timestamp('mapping_stale_at')->nullable();
            $table->text('mapping_stale_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sync_connectors', function (Blueprint $table) {
            $table->dropColumn(['mapping_stale_at', 'mapping_stale_reason']);
        });
    }
};
