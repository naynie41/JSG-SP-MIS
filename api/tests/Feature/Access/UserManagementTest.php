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
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private const STRONG_PASSWORD = 'Sup3rStr0ng!Pass';

    private User $admin;

    private User $mdaAdmin;

    private User $officer;   // MDA A, MDA Admin — a second MDA user (no user-admin rights)

    private User $userInB;

    private Mda $mdaA;

    private Mda $mdaB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        // No breached-password network calls in tests.
        Http::fake(['*' => Http::response('', 200)]);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $roleId = fn (RoleKey $k) => Role::where('key', $k->value)->firstOrFail()->id;

        $this->admin = User::factory()->create(['role_id' => $roleId(RoleKey::SystemAdministrator)]);
        $this->mdaAdmin = User::factory()->create(['mda_id' => $this->mdaA->id, 'role_id' => $roleId(RoleKey::MdaAdmin)]);
        $this->officer = User::factory()->create(['email' => 'officerA@example.test', 'mda_id' => $this->mdaA->id, 'role_id' => $roleId(RoleKey::MdaAdmin)]);
        $this->userInB = User::factory()->create(['email' => 'userB@example.test', 'mda_id' => $this->mdaB->id, 'role_id' => $roleId(RoleKey::MdaAdmin)]);
    }

    private function token(User $user): string
    {
        return $user->createToken('test')->plainTextToken;
    }

    private function mdaRoleId(): string
    {
        return Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id;
    }

    public function test_admin_can_create_user_scoped_to_mda_with_role(): void
    {
        $response = $this->withToken($this->token($this->admin))->postJson('/api/v1/users', [
            'name' => 'New Officer',
            'email' => 'new@example.test',
            'password' => self::STRONG_PASSWORD,
            'password_confirmation' => self::STRONG_PASSWORD,
            'mda_id' => $this->mdaA->id,
            'role_id' => $this->mdaRoleId(),
        ])->assertStatus(201)
            ->assertJsonPath('data.email', 'new@example.test')
            ->assertJsonPath('data.mda.id', $this->mdaA->id)
            ->assertJsonPath('data.role.key', RoleKey::MdaAdmin->value);

        $id = $response->json('data.id');
        $this->assertDatabaseHas('users', ['id' => $id, 'email' => 'new@example.test', 'mda_id' => $this->mdaA->id]);
        $this->assertDatabaseHas('audit_log', ['action' => 'user.created', 'entity_id' => $id]);
    }

    public function test_non_admin_cannot_create_user(): void
    {
        // MDA Officer lacks user.create.
        $this->withToken($this->token($this->officer))->postJson('/api/v1/users', [
            'name' => 'X', 'email' => 'x@example.test',
            'password' => self::STRONG_PASSWORD, 'password_confirmation' => self::STRONG_PASSWORD,
            'mda_id' => $this->mdaA->id, 'role_id' => $this->mdaRoleId(),
        ])->assertStatus(403)->assertJsonPath('error.code', 'FORBIDDEN');
    }

    public function test_user_list_is_scoped(): void
    {
        $emails = $this->withToken($this->token($this->mdaAdmin))
            ->getJson('/api/v1/users')->assertOk()->json('data.users.*.email');

        $this->assertContains('officerA@example.test', $emails);
        $this->assertNotContains('userB@example.test', $emails);
    }

    public function test_show_out_of_scope_user_returns_404(): void
    {
        $this->withToken($this->token($this->mdaAdmin))
            ->getJson("/api/v1/users/{$this->userInB->id}")
            ->assertStatus(404);
    }

    public function test_admin_can_suspend_and_deactivate_user_and_it_is_audited(): void
    {
        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/users/{$this->officer->id}/suspend")
            ->assertOk()->assertJsonPath('data.status', 'suspended');

        $this->assertDatabaseHas('users', ['id' => $this->officer->id, 'status' => 'suspended']);
        $this->assertDatabaseHas('audit_log', ['action' => 'user.updated', 'entity_id' => $this->officer->id]);

        $this->app['auth']->forgetGuards();
        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/users/{$this->officer->id}/deactivate")
            ->assertOk()->assertJsonPath('data.status', 'deactivated');
    }

    public function test_suspend_revokes_user_tokens(): void
    {
        $victimToken = $this->officer->createToken('victim')->plainTextToken;
        $this->assertDatabaseCount('personal_access_tokens', 1);

        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/users/{$this->officer->id}/suspend")->assertOk();

        // The officer's token is gone (admin's token remains → count 1).
        $this->app['auth']->forgetGuards();
        $this->withToken($victimToken)->getJson('/api/v1/auth/me')->assertUnauthorized();
    }

    /**
     * User administration is centralised with the System Administrator (FR-UAM-01).
     *
     * These two cases previously asserted the opposite — that an MDA Admin could create
     * a user inside its own MDA, and was only stopped at privilege/scope escalation.
     * That capability is withdrawn entirely: the MDA role no longer holds `user.create`
     * or `user.edit`, so the request never reaches the validation that used to bound it.
     */
    public function test_an_mda_cannot_create_a_user_even_in_its_own_mda(): void
    {
        $this->withToken($this->token($this->mdaAdmin))->postJson('/api/v1/users', [
            'name' => 'Owned', 'email' => 'owned@example.test',
            'password' => self::STRONG_PASSWORD, 'password_confirmation' => self::STRONG_PASSWORD,
            'mda_id' => $this->mdaA->id,
            'role_id' => $this->mdaRoleId(),
        ])->assertStatus(403);

        $this->assertDatabaseMissing('users', ['email' => 'owned@example.test']);
    }

    public function test_an_mda_cannot_edit_or_suspend_a_user(): void
    {
        $target = $this->officer;

        $this->withToken($this->token($this->mdaAdmin))
            ->patchJson("/api/v1/users/{$target->id}", ['name' => 'Renamed'])
            ->assertStatus(403);
        $this->app['auth']->forgetGuards();

        $this->withToken($this->token($this->mdaAdmin))
            ->postJson("/api/v1/users/{$target->id}/suspend")
            ->assertStatus(403);

        $this->assertNotSame('Renamed', $target->fresh()->name);
    }

    public function test_only_the_system_administrator_creates_mda_staff(): void
    {
        // The capability did not disappear — it moved. A System Administrator still
        // enrols MDA staff, which is the whole point of centralising it.
        $this->withToken($this->token($this->admin))->postJson('/api/v1/users', [
            'name' => 'Enrolled Centrally', 'email' => 'central@example.test',
            'password' => self::STRONG_PASSWORD, 'password_confirmation' => self::STRONG_PASSWORD,
            'mda_id' => $this->mdaA->id,
            'role_id' => $this->mdaRoleId(),
        ])->assertStatus(201);

        $this->assertDatabaseHas('users', ['email' => 'central@example.test', 'mda_id' => $this->mdaA->id]);
    }

    public function test_weak_password_is_rejected(): void
    {
        $this->withToken($this->token($this->admin))->postJson('/api/v1/users', [
            'name' => 'Weak', 'email' => 'weak@example.test',
            'password' => 'short', 'password_confirmation' => 'short',
            'mda_id' => $this->mdaA->id, 'role_id' => $this->mdaRoleId(),
        ])->assertStatus(422)->assertJsonPath('error.code', 'VALIDATION_ERROR');
    }

    public function test_force_password_reset_is_audited_and_revokes_tokens(): void
    {
        $victimToken = $this->officer->createToken('victim')->plainTextToken;

        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/users/{$this->officer->id}/force-password-reset")->assertOk();

        $this->assertDatabaseHas('audit_log', ['action' => 'user.password_reset_forced', 'entity_id' => $this->officer->id]);

        $this->app['auth']->forgetGuards();
        $this->withToken($victimToken)->getJson('/api/v1/auth/me')->assertUnauthorized();
    }

    public function test_reset_mfa_clears_mfa_and_is_audited(): void
    {
        $this->officer->forceFill([
            'mfa_enabled' => true,
            'mfa_secret' => 'JBSWY3DPEHPK3PXP',
            'mfa_recovery_codes' => ['AAAAA-BBBBB'],
        ])->save();

        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/users/{$this->officer->id}/reset-mfa")->assertOk();

        $fresh = User::withoutGlobalScopes()->find($this->officer->id);
        $this->assertFalse($fresh->mfa_enabled);
        $this->assertNull($fresh->mfa_secret);
        $this->assertDatabaseHas('audit_log', ['action' => 'user.mfa_reset', 'entity_id' => $this->officer->id]);
    }

    public function test_admin_can_update_user_status_directly(): void
    {
        $this->withToken($this->token($this->admin))
            ->patchJson("/api/v1/users/{$this->officer->id}", ['status' => UserStatus::Suspended->value])
            ->assertOk()->assertJsonPath('data.status', 'suspended');
    }
}
