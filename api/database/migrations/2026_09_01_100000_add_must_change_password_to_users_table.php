<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Administrator-initiated password reset (SECURITY.md §2, FR-UAM-06).
 *
 * Before this, `force-password-reset` only revoked tokens: the user signed back in
 * with the SAME password, and no route existed for an administrator to set one. A
 * user who forgot their password could not recover the account at all.
 *
 * Defaults to FALSE so existing accounts are untouched. Flagging everyone
 * retroactively would drop every current user — including the only administrator —
 * into a forced change screen on the next request.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('must_change_password')
                ->default(false)
                ->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('must_change_password');
        });
    }
};
