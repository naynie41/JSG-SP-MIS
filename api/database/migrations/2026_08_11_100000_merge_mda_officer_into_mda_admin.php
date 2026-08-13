<?php

declare(strict_types=1);

use App\Domain\Audit\Models\AuditLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Merge the MDA Officer role into MDA Admin (FR-UAM-01).
 *
 * The two MDA roles collapse into one: MDA Admin, whose permission set was already a
 * strict superset of the Officer's, so no capability is lost and every reassigned
 * account gains exactly the six Admin-only permissions.
 *
 * ORDER MATTERS. `users.role_id` is `nullOnDelete`, so dropping the role first would
 * leave every officer with `role_id = NULL` — not deleted, but stripped of all
 * permissions and denied everywhere. Accounts are therefore reassigned FIRST, asserted
 * to be zero, and only then is the role removed.
 *
 * Deliberately uses string literals, never `RoleKey::MdaOfficer` — the enum case is
 * removed in the same change, and a migration that references deleted code cannot be
 * replayed on a fresh database.
 *
 * Idempotent (a second run finds no officer role and returns) and reversible: each
 * reassignment is recorded in the append-only audit log, and `down()` reads those
 * entries back to restore exactly the accounts that were moved.
 */
return new class extends Migration
{
    private const OFFICER = 'mda_officer';

    private const ADMIN = 'mda_admin';

    /** The action name the reassignment is audited under, and read back by down(). */
    private const AUDIT_ACTION = 'user.role_reassigned';

    /**
     * The Officer permission set as it stood at merge time. Held here so `down()` can
     * rebuild the role without depending on a seeder that no longer defines it.
     *
     * @var list<string>
     */
    private const OFFICER_PERMISSIONS = [
        'mda.view', 'user.view',
        'beneficiary.view', 'beneficiary.create', 'beneficiary.edit',
        'beneficiary-lookup.view', 'household.view', 'household.create', 'household.edit',
        'programme.view',
        'activity.view', 'activity.create', 'activity.edit',
        'enrollment.view', 'enrollment.create', 'enrollment.edit',
        'benefit.view', 'benefit.create', 'benefit.approve',
        'referral.view', 'referral.create', 'referral.edit',
        'grievance.view', 'grievance.create', 'grievance.edit',
        'graduation.view', 'graduation.edit', 'dashboard.view', 'reporting.view', 'reporting.export',
    ];

    public function up(): void
    {
        $officerId = $this->roleId(self::OFFICER);
        if ($officerId === null) {
            return; // already merged — idempotent
        }

        $adminId = $this->roleId(self::ADMIN);
        if ($adminId === null) {
            // Refuse rather than strand accounts on a role that is about to disappear.
            throw new RuntimeException('Cannot merge MDA Officer: the mda_admin role is missing. Seed roles first.');
        }

        DB::transaction(function () use ($officerId, $adminId): void {
            $this->foldPermissions($officerId, $adminId);
            $this->reassignUsers($officerId, $adminId);

            $remaining = DB::table('users')->where('role_id', $officerId)->count();
            if ($remaining !== 0) {
                throw new RuntimeException("Refusing to drop the MDA Officer role: {$remaining} account(s) still hold it.");
            }

            DB::table('role_permission')->where('role_id', $officerId)->delete();
            DB::table('roles')->where('id', $officerId)->delete();
        });
    }

    /**
     * Restores the role and re-attaches its permissions, then moves back exactly the
     * accounts this migration moved — read from the audit trail it wrote.
     *
     * Accounts created as MDA Admin AFTER the merge are untouched: only users named in
     * a `user.role_reassigned` entry are reverted.
     */
    public function down(): void
    {
        if ($this->roleId(self::OFFICER) !== null) {
            return; // already restored
        }

        $officerId = (string) Str::uuid();
        DB::table('roles')->insert([
            'id' => $officerId,
            'key' => self::OFFICER,
            'name' => 'MDA Officer',
            'is_system' => true,
            'requires_mfa' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $permissionIds = DB::table('permissions')
            ->whereIn('key', self::OFFICER_PERMISSIONS)
            ->pluck('id');

        foreach ($permissionIds as $permissionId) {
            DB::table('role_permission')->insert([
                'role_id' => $officerId,
                'permission_id' => $permissionId,
            ]);
        }

        // The audit log is append-only, so the record of who was moved survives.
        $movedUserIds = DB::table('audit_log')
            ->where('action', self::AUDIT_ACTION)
            ->pluck('entity_id')
            ->filter()
            ->unique();

        foreach ($movedUserIds as $userId) {
            DB::table('users')->where('id', $userId)->update(['role_id' => $officerId]);
        }
    }

    private function roleId(string $key): ?string
    {
        $id = DB::table('roles')->where('key', $key)->value('id');

        return $id === null ? null : (string) $id;
    }

    /**
     * Give MDA Admin any permission the Officer held that it lacks. Admin is already a
     * superset, so this is expected to add nothing — it runs anyway so the merge is
     * correct even if the sets have drifted in a given environment.
     */
    private function foldPermissions(string $officerId, string $adminId): void
    {
        $officerPermissions = DB::table('role_permission')->where('role_id', $officerId)->pluck('permission_id');
        $adminPermissions = DB::table('role_permission')->where('role_id', $adminId)->pluck('permission_id')->all();

        foreach ($officerPermissions->diff($adminPermissions) as $permissionId) {
            DB::table('role_permission')->insert([
                'role_id' => $adminId,
                'permission_id' => $permissionId,
            ]);
        }
    }

    /**
     * Reassign each account and audit it individually. This is a privilege ESCALATION
     * per account (the six Admin-only permissions), so it has to be attributable per
     * user rather than recorded as one bulk statement — and `actor_id` is null because
     * a migration is the system acting, not a person.
     */
    private function reassignUsers(string $officerId, string $adminId): void
    {
        $users = DB::table('users')
            ->where('role_id', $officerId)
            ->get(['id', 'mda_id', 'status']);

        foreach ($users as $user) {
            // Only role_id changes: MDA scope and account status are preserved, and a
            // suspended account stays suspended rather than being quietly reactivated.
            DB::table('users')->where('id', $user->id)->update([
                'role_id' => $adminId,
                'updated_at' => now(),
            ]);

            // Written through the model, NOT a raw insert: the hash chain is computed in
            // AuditLog's creating hook, so a raw insert would append rows the verifier
            // cannot check — indistinguishable from tampering.
            AuditLog::create([
                'actor_id' => null, // the system, not a person — a migration has no user
                'actor_mda_id' => $user->mda_id,
                'action' => self::AUDIT_ACTION,
                'entity_type' => 'user',
                'entity_id' => $user->id,
                'before' => ['role' => self::OFFICER],
                'after' => [
                    'role' => self::ADMIN,
                    'reason' => 'MDA Officer role merged into MDA Admin (FR-UAM-01)',
                    'mda_id_preserved' => $user->mda_id,
                    'status_preserved' => $user->status,
                ],
                'created_at' => now(),
            ]);
        }
    }
};
