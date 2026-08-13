<?php

declare(strict_types=1);

namespace Tests\Feature\Access;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * The MDA Officer → MDA Admin data migration (FR-UAM-01).
 *
 * The migration already ran as part of the suite's schema build, when there were no
 * officer accounts to move — which proves it is a safe no-op but nothing else. These
 * tests recreate the pre-merge state (the role, and users holding it) and run the
 * migration against it, because the risk here is entirely in the data step:
 *
 *  - `users.role_id` is `nullOnDelete`, so dropping the role before reassigning would
 *    leave accounts with no role — not deleted, but denied everywhere;
 *  - accounts must keep their MDA and their status, including a suspended one, which
 *    must not be quietly reactivated by the move;
 *  - the reassignment is a privilege ESCALATION per account and has to be attributable.
 */
class MdaOfficerMergeMigrationTest extends TestCase
{
    use RefreshDatabase;

    private const MIGRATION = 'database/migrations/2026_08_11_100000_merge_mda_officer_into_mda_admin.php';

    private Mda $mda;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
    }

    /** Recreate the pre-merge world: the officer role, with the permissions it had. */
    private function restoreOfficerRole(): string
    {
        $id = (string) Str::uuid();

        DB::table('roles')->insert([
            'id' => $id,
            'key' => 'mda_officer',
            'name' => 'MDA Officer',
            'is_system' => true,
            'requires_mfa' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // The Officer's set, minus the six that were Admin-only.
        $keys = DB::table('permissions')
            ->whereIn('key', ['mda.view', 'user.view', 'beneficiary.view', 'beneficiary.create', 'dashboard.view'])
            ->pluck('id');

        foreach ($keys as $permissionId) {
            DB::table('role_permission')->insert(['role_id' => $id, 'permission_id' => $permissionId]);
        }

        return $id;
    }

    private function officerUser(string $roleId, UserStatus $status = UserStatus::Active): User
    {
        $user = User::factory()->create(['mda_id' => $this->mda->id, 'status' => $status]);
        DB::table('users')->where('id', $user->id)->update(['role_id' => $roleId]);

        return $user->fresh();
    }

    private function runMigration(): void
    {
        $migration = require base_path(self::MIGRATION);
        $migration->up();
    }

    private function rollBackMigration(): void
    {
        $migration = require base_path(self::MIGRATION);
        $migration->down();
    }

    private function adminRoleId(): string
    {
        return (string) Role::where('key', RoleKey::MdaAdmin->value)->value('id');
    }

    /* ------------------------------------------------------------ reassignment */

    public function test_it_reassigns_officers_and_then_removes_the_role(): void
    {
        $officerRoleId = $this->restoreOfficerRole();
        $one = $this->officerUser($officerRoleId);
        $two = $this->officerUser($officerRoleId);

        $this->runMigration();

        foreach ([$one, $two] as $user) {
            $this->assertSame($this->adminRoleId(), $user->fresh()->role_id);
        }

        // The role is gone only AFTER everyone left it.
        $this->assertNull(Role::where('key', 'mda_officer')->first());
        $this->assertDatabaseCount('users', User::query()->withoutGlobalScopes()->count());
    }

    public function test_no_account_is_deleted_or_left_without_a_role(): void
    {
        $officerRoleId = $this->restoreOfficerRole();
        $user = $this->officerUser($officerRoleId);

        $this->runMigration();

        $fresh = $user->fresh();
        $this->assertNotNull($fresh, 'the account must survive the merge');
        $this->assertNotNull($fresh->role_id, 'a null role_id would deny the user everywhere');
    }

    public function test_it_preserves_mda_scope_and_account_status(): void
    {
        $officerRoleId = $this->restoreOfficerRole();
        $suspended = $this->officerUser($officerRoleId, UserStatus::Suspended);
        $active = $this->officerUser($officerRoleId, UserStatus::Active);

        $this->runMigration();

        // A suspended officer must not be reactivated by being reassigned.
        $this->assertSame(UserStatus::Suspended, $suspended->fresh()->status);
        $this->assertSame(UserStatus::Active, $active->fresh()->status);

        foreach ([$suspended, $active] as $user) {
            $this->assertSame($this->mda->id, $user->fresh()->mda_id, 'MDA scope must be untouched');
        }
    }

    public function test_a_reassigned_account_gains_the_admin_capabilities(): void
    {
        $officerRoleId = $this->restoreOfficerRole();
        $user = $this->officerUser($officerRoleId);

        $this->assertFalse($user->fresh()->hasPermission('beneficiary.approve'));

        $this->runMigration();

        $fresh = User::query()->withoutGlobalScopes()->findOrFail($user->id);
        $this->assertTrue($fresh->hasPermission('beneficiary.approve'), 'the merge grants the approval capability');
        $this->assertTrue($fresh->hasPermission('beneficiary.export'));
        // …and never user administration.
        $this->assertFalse($fresh->hasPermission('user.create'));
    }

    /* ------------------------------------------------------------------ audit */

    public function test_each_reassignment_is_audited_and_attributable(): void
    {
        $officerRoleId = $this->restoreOfficerRole();
        $one = $this->officerUser($officerRoleId);
        $two = $this->officerUser($officerRoleId);

        $this->runMigration();

        foreach ([$one, $two] as $user) {
            $this->assertDatabaseHas('audit_log', [
                'action' => 'user.role_reassigned',
                'entity_id' => $user->id,
            ]);
        }

        $entry = DB::table('audit_log')->where('action', 'user.role_reassigned')->first();

        // A privilege escalation must say what it moved from and to.
        $this->assertStringContainsString('mda_officer', (string) $entry->before);
        $this->assertStringContainsString('mda_admin', (string) $entry->after);
        // The system did this, not a person — so actor_id is null rather than invented.
        $this->assertNull($entry->actor_id);
        // Written through the model, so the entry joins the hash chain.
        $this->assertNotNull($entry->chain_position);
        $this->assertNotNull($entry->entry_hash);
    }

    /* ------------------------------------------------- idempotency + rollback */

    public function test_running_it_twice_changes_nothing(): void
    {
        $officerRoleId = $this->restoreOfficerRole();
        $this->officerUser($officerRoleId);

        $this->runMigration();
        $auditCount = DB::table('audit_log')->where('action', 'user.role_reassigned')->count();

        $this->runMigration(); // second run: no officer role, returns immediately

        $this->assertSame(
            $auditCount,
            DB::table('audit_log')->where('action', 'user.role_reassigned')->count(),
            'a re-run must not re-audit anything',
        );
    }

    public function test_it_is_a_no_op_when_the_role_was_never_there(): void
    {
        $this->assertNull(Role::where('key', 'mda_officer')->first());

        $this->runMigration();

        $this->assertSame(0, DB::table('audit_log')->where('action', 'user.role_reassigned')->count());
    }

    public function test_rollback_restores_the_role_and_exactly_the_accounts_it_moved(): void
    {
        $officerRoleId = $this->restoreOfficerRole();
        $moved = $this->officerUser($officerRoleId);

        // An account that was ALREADY an MDA Admin before the merge.
        $untouched = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => $this->adminRoleId(),
        ]);

        $this->runMigration();
        $this->rollBackMigration();

        $restoredRole = Role::where('key', 'mda_officer')->first();
        $this->assertNotNull($restoredRole);

        // Only the reassigned account goes back — read from the append-only audit trail.
        $this->assertSame($restoredRole->id, $moved->fresh()->role_id);
        $this->assertSame($this->adminRoleId(), $untouched->fresh()->role_id, 'a pre-existing Admin must not be demoted');
    }
}
