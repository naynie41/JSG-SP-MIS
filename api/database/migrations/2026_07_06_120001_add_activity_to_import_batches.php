<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Activity-first upload (PRD §9, FR-REG-10/FR-PRG-05): every beneficiary import is
 * bound to a registered activity that the importing MDA owns, so the resulting
 * intervention (an enrollment) is recorded under that activity. The column is
 * required — an upload with no activity is refused at the request layer.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_batches', function (Blueprint $table) {
            $table->uuid('activity_id')->after('source');
            $table->foreign('activity_id')->references('id')->on('activities')->cascadeOnDelete();
            $table->index('activity_id');
        });
    }

    public function down(): void
    {
        Schema::table('import_batches', function (Blueprint $table) {
            $table->dropForeign(['activity_id']);
            $table->dropIndex(['activity_id']);
            $table->dropColumn('activity_id');
        });
    }
};
