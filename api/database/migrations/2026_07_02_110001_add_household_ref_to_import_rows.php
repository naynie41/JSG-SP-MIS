<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Source household-reference on staged import rows (PRD FR-REG-02, §9). When a
 * source file/record carries a household grouping key, the commit step forms the
 * household and opens a membership. Beneficiary-only sources leave these null.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('import_rows', function (Blueprint $table) {
            $table->string('household_ref')->nullable()->after('original_record_id');
            $table->string('household_role')->nullable()->after('household_ref');
            $table->boolean('household_head')->default(false)->after('household_role');
        });
    }

    public function down(): void
    {
        Schema::table('import_rows', function (Blueprint $table) {
            $table->dropColumn(['household_ref', 'household_role', 'household_head']);
        });
    }
};
