<?php

declare(strict_types=1);

namespace Tests\Feature\Access;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Administrator-initiated password reset and the forced change that follows
 * (FR-UAM-06, SECURITY.md §2).
 *
 * Before this existed, `force-password-reset` only revoked tokens: the user signed
 * back in with the SAME password and no route let an administrator set one, so a
 * forgotten password meant a permanently unusable account.
 */
class ForcedPasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $officer;

    private Mda $mda;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        // No breached-password network calls in tests.
        Http::fake(['*' => Http::response('', 200)]);

        $this->mda = Mda::factory()->create(['name' => 'MDA A']);
        $roleId = fn (RoleKey $k) => Role::where('key', $k->value)->firstOrFail()->id;

        $this->admin = User::factory()->create(['role_id' => $roleId(RoleKey::SystemAdministrator)]);
        $this->officer = User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => $roleId(RoleKey::MdaAdmin),
        ]);
    }

    private function token(User $user): string
    {
        return $user->createToken('test')->plainTextToken;
    }

    // ---------------------------------------------------------------- the reset

    public function test_force_reset_returns_a_working_temporary_password(): void
    {
        $response = $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/users/{$this->officer->id}/force-password-reset")
            ->assertOk();

        $temporary = $response->json('data.temporary_password');

        $this->assertIsString($temporary);
        $this->assertGreaterThanOrEqual(12, strlen($temporary));

        // It must actually be the account's password now — the old bug was that
        // nothing changed, so the user kept signing in with the forgotten one.
        $this->assertTrue(Hash::check($temporary, $this->officer->fresh()->password));
    }

    public function test_force_reset_sets_the_flag_and_revokes_sessions(): void
    {
        $liveToken = $this->officer->createToken('live')->plainTextToken;

        $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/users/{$this->officer->id}/force-password-reset")
            ->assertOk();

        $this->assertTrue((bool) $this->officer->fresh()->must_change_password);

        $this->app['auth']->forgetGuards();
        $this->withToken($liveToken)->getJson('/api/v1/auth/me')->assertUnauthorized();
    }

    public function test_temporary_password_is_never_written_to_the_audit_log(): void
    {
        $response = $this->withToken($this->token($this->admin))
            ->postJson("/api/v1/users/{$this->officer->id}/force-password-reset")
            ->assertOk();

        $temporary = (string) $response->json('data.temporary_password');

        $this->assertDatabaseHas('audit_log', [
            'action' => 'user.password_reset_forced',
            'entity_id' => $this->officer->id,
        ]);

        // Scan the whole row, not one column: a temporary password must not reach
        // `before`, `after`, or anywhere else in the trail.
        foreach (DB::table('audit_log')->get() as $row) {
            $this->assertStringNotContainsString(
                $temporary,
                (string) json_encode($row),
                'The temporary password leaked into the audit log.',
            );
        }
    }

    // ------------------------------------------------------------- the blocking

    public function test_flagged_user_is_blocked_from_ordinary_endpoints(): void
    {
        $this->officer->forceFill(['must_change_password' => true])->save();

        $this->withToken($this->token($this->officer))
            ->getJson('/api/v1/users')
            ->assertStatus(403)
            ->assertJsonPath('error.code', 'PASSWORD_CHANGE_REQUIRED');
    }

    public function test_flagged_user_may_still_reach_me_logout_and_password(): void
    {
        $this->officer->forceFill(['must_change_password' => true])->save();
        $token = $this->token($this->officer);

        $this->withToken($token)->getJson('/api/v1/auth/me')->assertOk();

        // The change endpoint itself must never be blocked, or the user is stuck.
        $this->withToken($token)->postJson('/api/v1/auth/password', [
            'current_password' => 'password',
            'password' => 'An0ther!Str0ngPass',
            'password_confirmation' => 'An0ther!Str0ngPass',
        ])->assertOk();
    }

    public function test_unauthenticated_requests_are_unaffected(): void
    {
        // The middleware is global; it must not turn anonymous failures into 403s.
        // 401 (bad credentials) is the correct answer here — the point is that it is
        // NOT PASSWORD_CHANGE_REQUIRED.
        $this->postJson('/api/v1/auth/login', [
            'email' => 'nobody@example.test',
            'password' => 'wrong-password',
        ])->assertStatus(401);

        // And a token-less call to a protected route stays 401, not 403.
        $this->getJson('/api/v1/users')->assertUnauthorized();
    }

    // ------------------------------------------------------------- the clearing

    public function test_changing_the_password_clears_the_flag_and_restores_access(): void
    {
        $this->officer->forceFill(['must_change_password' => true])->save();

        $this->withToken($this->token($this->officer))->postJson('/api/v1/auth/password', [
            'current_password' => 'password',
            'password' => 'Ch0sen!ByTheUser1',
            'password_confirmation' => 'Ch0sen!ByTheUser1',
        ])->assertOk();

        $this->assertFalse((bool) $this->officer->fresh()->must_change_password);

        // Changing the password revokes tokens, so sign in again and confirm the
        // user is no longer blocked.
        $this->app['auth']->forgetGuards();
        $fresh = $this->token($this->officer->fresh());
        $this->withToken($fresh)->getJson('/api/v1/auth/me')->assertOk();
    }

    public function test_flag_cannot_be_cleared_by_mass_assignment(): void
    {
        $this->officer->forceFill(['must_change_password' => true])->save();

        // Not fillable, so a crafted payload must not clear it even on a route the
        // user is otherwise allowed to call.
        $this->withToken($this->token($this->admin))
            ->putJson("/api/v1/users/{$this->officer->id}", [
                'name' => 'Renamed',
                'must_change_password' => false,
            ])->assertOk();

        $this->assertTrue((bool) $this->officer->fresh()->must_change_password);
    }

    // ------------------------------------------------------------ account setup

    public function test_admin_created_accounts_start_flagged(): void
    {
        $roleId = Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id;

        $response = $this->withToken($this->token($this->admin))
            ->postJson('/api/v1/users', [
                'name' => 'New Officer',
                'email' => 'new.officer@example.test',
                'password' => 'Sup3rStr0ng!Pass',
                'password_confirmation' => 'Sup3rStr0ng!Pass',
                'mda_id' => $this->mda->id,
                'role_id' => $roleId,
            ])->assertCreated();

        $created = User::where('email', 'new.officer@example.test')->firstOrFail();

        $this->assertTrue((bool) $created->must_change_password);
        $this->assertTrue($response->json('data.must_change_password'));
    }

    public function test_create_admin_command_does_not_flag_the_operator(): void
    {
        // The operator running the CLI chooses their own password at the prompt, so
        // there is no shared secret to retire and no change to force.
        $this->artisan('spmis:create-admin', ['email' => 'cli.admin@example.test'])
            ->expectsQuestion('Password (min 12 chars, not a breached password)', 'Sup3rStr0ng!Pass')
            ->expectsQuestion('Confirm password', 'Sup3rStr0ng!Pass')
            ->assertExitCode(0);

        $created = User::where('email', 'cli.admin@example.test')->firstOrFail();

        $this->assertFalse((bool) $created->must_change_password);
    }
}
