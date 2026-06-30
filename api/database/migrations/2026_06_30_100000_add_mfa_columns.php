<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * MFA support (PRD FR-UAM-04):
 *  - roles.requires_mfa: drives the server-side rule that MFA is mandatory for
 *    System Administrator and Executive roles (data-driven, configurable).
 *  - users.mfa_recovery_codes: one-time recovery codes, encrypted at rest at the
 *    model layer (SECURITY.md §2). The TOTP secret column already exists.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('requires_mfa')->default(false)->after('is_system');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->text('mfa_recovery_codes')->nullable(); // encrypted:array at model layer
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('requires_mfa');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mfa_recovery_codes');
        });
    }
};
