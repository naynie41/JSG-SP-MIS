<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Conditional beneficiary involvement on an activity (PRD §10). `involves_beneficiaries`
 * records whether the activity onboards/serves beneficiaries at all: when false the
 * activity is saved alone (no target, no upload); when true a target and a beneficiary
 * upload are mandatory. `target_count` is renamed to the clearer `target_beneficiaries`
 * (it always meant the target beneficiary count) — required when involving, null otherwise.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->boolean('involves_beneficiaries')->default(false)->after('owner_mda_id');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->renameColumn('target_count', 'target_beneficiaries');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->renameColumn('target_beneficiaries', 'target_count');
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('involves_beneficiaries');
        });
    }
};
