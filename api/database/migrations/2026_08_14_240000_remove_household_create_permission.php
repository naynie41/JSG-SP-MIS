<?php

declare(strict_types=1);

use App\Domain\Access\Services\PermissionSynchronizer;
use App\Domain\Audit\Models\AuditLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Removes the unconsumable `household.create` permission.
 *
 * Households are formed by source ingestion from the household-reference field
 * (CLAUDE.md §8) — there is no create endpoint, no policy ability, and no code path that
 * ever asked for this permission. Granting it made the RBAC set claim a capability the
 * system does not have, which is the kind of drift that makes a permissions review
 * meaningless: a reviewer reading the MDA role would conclude MDAs can create households
 * by hand.
 *
 * A migration is required rather than just dropping the registration:
 * {@see PermissionSynchronizer} upserts and deliberately
 * never deletes, so an existing deployment would keep both the permission row and the
 * role grants pointing at it.
 *
 * `role_permission` cascades on delete, so removing the permission removes the grants.
 * The change is audited because it narrows what roles can do.
 */
return new class extends Migration
{
    private const KEY = 'household.create';

    public function up(): void
    {
        $permission = DB::table('permissions')->where('key', self::KEY)->first();

        if ($permission === null) {
            return; // fresh install — the seeder never defined it
        }

        $roleKeys = DB::table('role_permission')
            ->join('roles', 'roles.id', '=', 'role_permission.role_id')
            ->where('role_permission.permission_id', $permission->id)
            ->pluck('roles.key')
            ->all();

        // Deleting the permission cascades to `role_permission`.
        DB::table('permissions')->where('id', $permission->id)->delete();

        // Written through the model so it joins the audit hash chain; actor is null
        // because a migration has no authenticated user.
        AuditLog::create([
            'actor_id' => null,
            'actor_mda_id' => null,
            'action' => 'permission.removed',
            'entity_type' => 'permission',
            'entity_id' => $permission->id,
            'before' => ['key' => self::KEY, 'granted_to_roles' => $roleKeys],
            'after' => null,
            'created_at' => now(),
        ]);
    }

    /**
     * Re-creates the permission but grants it to NO role.
     *
     * Rolling back should restore the schema, not re-introduce a capability claim that
     * was wrong. Nothing consumed this permission, so no role needs it back for the
     * system to work.
     */
    public function down(): void
    {
        if (DB::table('permissions')->where('key', self::KEY)->exists()) {
            return;
        }

        DB::table('permissions')->insert([
            'id' => (string) Str::uuid7(),
            'key' => self::KEY,
            'module' => 'household',
            'action' => 'create',
            'description' => 'Create households',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
