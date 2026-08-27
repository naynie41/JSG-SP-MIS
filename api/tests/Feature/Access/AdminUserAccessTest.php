<?php

declare(strict_types=1);

namespace Tests\Feature\Access;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Access\Services\LoginActivityService;
use App\Domain\Audit\Models\AuditLog;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Phase 1 user/access administration as surfaced by the console. The console COMPOSES
 * the existing Phase 1 endpoints and policies — these tests assert that reuse (the same
 * `/users`, `/roles`, `/permissions` capability and its permission gating still governs
 * every action) plus the two additions the console needs: lock/MFA-policy state on the
 * user resource, and the read-only login-activity projection of the audit log.
 */
class AdminUserAccessTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'MDA A']);
        $this->users['admin'] = $this->user(null, RoleKey::SystemAdministrator);
        $this->users['officer'] = $this->user($this->mda, RoleKey::MdaAdmin);
        $this->users['coordination'] = $this->user(null, RoleKey::SpCoordination);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function as(string $key, string $method, string $url, array $data = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)
            ->json($method, $url, $data);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /* ------------------------------------------- reuse of the Phase 1 capability */

    public function test_the_console_manages_users_through_the_existing_phase_1_endpoints(): void
    {
        // The password policy checks HaveIBeenPwned over the network. Left real, this
        // test makes a live external call whose verdict depends on a breach corpus that
        // changes without us — which is exactly how it started failing on a fixture
        // password that used to pass. Faked, as {@see AuthTest} already does.
        Http::fake(['*' => Http::response('', 200)]); // uncompromised() => not breached

        $target = $this->users['officer'];

        // Create — the existing /users endpoint, with MDA + role assignment.
        $mdaAdminRole = Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail();
        $created = $this->as('admin', 'POST', '/api/v1/users', [
            'name' => 'New Officer',
            'email' => 'new.officer@example.test',
            'password' => 'Str0ng!Passw0rd',
            'password_confirmation' => 'Str0ng!Passw0rd',
            'role_id' => $mdaAdminRole->id,
            'mda_id' => $this->mda->id,
        ])->assertCreated()->json('data');
        $this->assertSame('mda_admin', $created['role']['key']);
        $this->assertSame('MDA A', $created['mda']['name']);

        // Edit — reassign role/MDA through the same endpoint.
        $this->as('admin', 'PATCH', "/api/v1/users/{$target->id}", ['name' => 'Renamed Officer'])
            ->assertOk()->assertJsonPath('data.name', 'Renamed Officer');

        // Status lifecycle — suspend, then activate.
        $this->as('admin', 'POST', "/api/v1/users/{$target->id}/suspend")
            ->assertOk()->assertJsonPath('data.status', 'suspended');
        $this->as('admin', 'POST', "/api/v1/users/{$target->id}/activate")
            ->assertOk()->assertJsonPath('data.status', 'active');

        // MFA + password administration.
        $this->as('admin', 'POST', "/api/v1/users/{$target->id}/reset-mfa")->assertOk();
        $this->as('admin', 'POST', "/api/v1/users/{$target->id}/force-password-reset")->assertOk();

        // Roles + permissions are read through the existing access endpoints.
        $this->as('admin', 'GET', '/api/v1/roles')->assertOk()->assertJsonStructure(['data' => ['roles']]);
        $this->as('admin', 'GET', '/api/v1/permissions')->assertOk()->assertJsonStructure(['data' => ['modules']]);
        $this->as('admin', 'GET', '/api/v1/access/matrix')->assertOk();
    }

    public function test_phase_1_permission_gating_still_governs_every_action(): void
    {
        $target = $this->users['officer'];

        // An MDA officer holds `user.view` (Phase 1 policy) but NONE of the
        // administration permissions — the console inherits that split rather than
        // defining its own.
        $this->as('officer', 'POST', '/api/v1/users', ['name' => 'X'])->assertStatus(403);
        $this->as('officer', 'PATCH', "/api/v1/users/{$target->id}", ['name' => 'X'])->assertStatus(403);
        $this->as('officer', 'POST', "/api/v1/users/{$target->id}/suspend")->assertStatus(403);
        $this->as('officer', 'POST', "/api/v1/users/{$target->id}/reset-mfa")->assertStatus(403);
        $this->as('officer', 'POST', "/api/v1/users/{$target->id}/force-password-reset")->assertStatus(403);

        // SP Coordination may VIEW users/roles but not administer them.
        $this->as('coordination', 'GET', '/api/v1/users')->assertOk();
        $this->as('coordination', 'POST', "/api/v1/users/{$target->id}/suspend")->assertStatus(403);
    }

    /* -------------------------------------------------- account status + MFA policy */

    public function test_the_user_resource_exposes_lock_state_and_mfa_policy(): void
    {
        $officer = $this->users['officer'];
        // Lockout columns are deliberately NOT mass-assignable (SECURITY.md) — they are
        // managed by application logic, so the fixture sets them the same way.
        $officer->forceFill(['locked_until' => Carbon::now()->addMinutes(15)])->save();

        $rows = collect($this->as('admin', 'GET', '/api/v1/users')->assertOk()->json('data.users'));
        $row = $rows->firstWhere('id', $officer->id);

        // Lockout is a runtime state distinct from the stored status.
        $this->assertSame('active', $row['status']);
        $this->assertTrue($row['is_locked']);
        $this->assertNotNull($row['locked_until']);

        // MFA: enrolment vs the role-driven requirement (the enforcement policy).
        $this->assertArrayHasKey('mfa_enabled', $row);
        $this->assertArrayHasKey('mfa_required', $row);

        // An expired lock is no longer locked.
        $officer->forceFill(['locked_until' => Carbon::now()->subMinute()])->save();
        $rows = collect($this->as('admin', 'GET', '/api/v1/users')->assertOk()->json('data.users'));
        $this->assertFalse($rows->firstWhere('id', $officer->id)['is_locked']);
    }

    /* ------------------------------------------------------------- login activity */

    public function test_login_activity_projects_the_existing_audit_trail(): void
    {
        $this->audit('auth.login', $this->users['officer'], '10.0.0.1');
        $this->audit('auth.login_failed', $this->users['officer'], '10.0.0.2');
        $this->audit('auth.account_locked', $this->users['officer'], '10.0.0.3');
        $this->audit('user.mfa_reset', $this->users['admin'], '10.0.0.4');
        // Not an authentication event — must not appear.
        $this->audit('beneficiary.created', $this->users['officer'], '10.0.0.5');

        $data = $this->as('admin', 'GET', '/api/v1/admin/login-activity')->assertOk()->json('data');

        $this->assertSame(1, $data['summary']['logins']);
        $this->assertSame(1, $data['summary']['failed_logins']);
        $this->assertSame(1, $data['summary']['lockouts']);
        $this->assertSame(1, $data['summary']['mfa_resets']);

        $actions = array_column($data['entries'], 'action');
        $this->assertCount(4, $actions);
        $this->assertNotContains('beneficiary.created', $actions);

        // Outcomes drive the UI's status treatment.
        $byAction = collect($data['entries'])->keyBy('action');
        $this->assertSame('success', $byAction['auth.login']['outcome']);
        $this->assertSame('failure', $byAction['auth.login_failed']['outcome']);
        $this->assertSame('security', $byAction['auth.account_locked']['outcome']);

        // The envelope only — audit before/after payloads never surface.
        $this->assertSame($this->users['officer']->name, $byAction['auth.login']['actor']);
        $this->assertSame('10.0.0.1', $byAction['auth.login']['ip_address']);
        $this->assertArrayNotHasKey('before', $byAction['auth.login']);
        $this->assertArrayNotHasKey('after', $byAction['auth.login']);
    }

    public function test_login_activity_can_be_narrowed_to_one_user(): void
    {
        $this->audit('auth.login', $this->users['officer']);
        $this->audit('auth.login', $this->users['admin']);

        $officerId = $this->users['officer']->id;
        $data = $this->as('admin', 'GET', "/api/v1/admin/login-activity?user_id={$officerId}")->assertOk()->json('data');

        $this->assertCount(1, $data['entries']);
        $this->assertSame($this->users['officer']->name, $data['entries'][0]['actor']);
    }

    public function test_login_activity_is_gated_to_the_system_administrator(): void
    {
        foreach (['officer', 'coordination'] as $key) {
            $this->as($key, 'GET', '/api/v1/admin/login-activity')
                ->assertStatus(403)->assertJsonPath('error.code', 'FORBIDDEN');
        }
    }

    public function test_login_activity_only_reads_authentication_actions(): void
    {
        // The whitelist is explicit, so an unrelated audited action can never leak in.
        $this->assertContains('auth.login', LoginActivityService::ACTIONS);
        $this->assertNotContains('beneficiary.created', LoginActivityService::ACTIONS);
    }

    private function audit(string $action, User $actor, ?string $ip = null): void
    {
        AuditLog::create([
            'actor_id' => $actor->id,
            'actor_mda_id' => $actor->mda_id,
            'action' => $action,
            'entity_type' => User::class,
            'entity_id' => $actor->id,
            'ip_address' => $ip,
        ]);
    }
}
