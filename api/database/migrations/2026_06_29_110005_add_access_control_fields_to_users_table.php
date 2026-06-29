<?php

declare(strict_types=1);

use App\Domain\Access\Enums\UserStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds access-control and security columns to users:
 *  - mda_id: the MDA the user belongs to (PRD FR-UAM-02). Nullable because
 *    some roles (e.g. Executive, System Administrator) are not MDA-bound.
 *  - status: account lifecycle (PRD FR-UAM-02).
 *  - MFA fields: TOTP secret (encrypted at the model layer per SECURITY.md §2)
 *    and an enabled flag (PRD FR-UAM-04).
 *  - Lockout fields: failed attempt counter + lock expiry (PRD FR-UAM-06).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('mda_id')->nullable();
            $table->string('status')->default(UserStatus::Active->value);
            $table->text('mfa_secret')->nullable();        // encrypted via model cast
            $table->boolean('mfa_enabled')->default(false);
            $table->unsignedInteger('failed_login_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();
            $table->timestamp('last_login_at')->nullable();

            $table->index('mda_id');
            $table->index('status');

            $table->foreign('mda_id')->references('id')->on('mdas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['mda_id']);
            $table->dropIndex(['mda_id']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'mda_id',
                'status',
                'mfa_secret',
                'mfa_enabled',
                'failed_login_attempts',
                'locked_until',
                'last_login_at',
            ]);
        });
    }
};
