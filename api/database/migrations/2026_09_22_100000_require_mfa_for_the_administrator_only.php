<?php

declare(strict_types=1);

use App\Domain\Access\Enums\RoleKey;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * MFA is mandatory for the System Administrator alone (revises PRD FR-UAM-04,
 * owner's decision 2026-09-22). It was previously also mandatory for Executive.
 *
 * Why a migration as well as the seeder edit: `RolesAndPermissionsSeeder` is the
 * source of truth for this flag, but it does not run on every deploy — migrations
 * do. Without this, the change would silently not take effect on an environment
 * where nobody remembered to re-seed, and the symptom (an executive still being
 * asked to enrol) would look like the code had not shipped.
 *
 * What this does NOT do, deliberately: it does not disable MFA for anyone who has
 * already enrolled. `users.mfa_enabled` is untouched, so an executive who set it up
 * keeps it and keeps being challenged at login — the difference is that it is now
 * optional rather than enforced. Reaching into people's accounts to switch off a
 * security control they chose is not something a migration should do.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('roles')
            ->where('key', '!=', RoleKey::SystemAdministrator->value)
            ->update(['requires_mfa' => false]);
    }

    public function down(): void
    {
        // Restores the previous rule: System Administrator and Executive.
        DB::table('roles')
            ->where('key', RoleKey::Executive->value)
            ->update(['requires_mfa' => true]);
    }
};
