<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Denormalise the recipient's LGA/Ward onto each benefit at record time
 * (PRD FR-BEN-03). This makes coverage aggregation by LGA/Ward a join-free,
 * indexed GROUP BY on the ledger — efficient and cache-friendly for the Phase 6
 * dashboards.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('benefits', function (Blueprint $table) {
            $table->string('lga')->nullable()->after('delivery_date');
            $table->string('ward')->nullable()->after('lga');

            $table->index('lga');
            $table->index(['mda_id', 'delivery_date']);
        });
    }

    public function down(): void
    {
        Schema::table('benefits', function (Blueprint $table) {
            $table->dropIndex(['lga']);
            $table->dropIndex(['mda_id', 'delivery_date']);
            $table->dropColumn(['lga', 'ward']);
        });
    }
};
