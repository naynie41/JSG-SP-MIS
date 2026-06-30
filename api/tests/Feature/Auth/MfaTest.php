<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Events\MfaChallengeFailed;
use App\Domain\Access\Events\MfaEnrolled;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class MfaTest extends TestCase
{
    use RefreshDatabase;

    private const PASSWORD = 'Sup3rStr0ng!Pass';

    private function makeUser(RoleKey $roleKey = RoleKey::MdaOfficer): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $role = Role::where('key', $roleKey->value)->firstOrFail();

        return User::factory()->create([
            'email' => 'user@example.test',
            'password' => self::PASSWORD,
            'mda_id' => Mda::factory()->create()->id,
            'role_id' => $role->id,
        ]);
    }

    private function totp(string $secret): string
    {
        return (new Google2FA)->getCurrentOtp($secret);
    }

    private function login(string $password = self::PASSWORD): \Illuminate\Testing\TestResponse
    {
        return $this->postJson('/api/v1/auth/login', [
            'email' => 'user@example.test',
            'password' => $password,
        ]);
    }

    public function test_optional_user_can_enroll_verify_and_login_with_totp(): void
    {
        Event::fake([MfaEnrolled::class]);
        $this->makeUser();

        // Plain login (no MFA yet) → full token.
        $token = $this->login()->assertOk()->json('data.token');
        $this->assertNotNull($token);

        // Enroll.
        $enroll = $this->withToken($token)->postJson('/api/v1/auth/mfa/enroll')->assertOk();
        $secret = $enroll->json('data.secret');
        $this->assertNotEmpty($secret);
        $this->assertStringStartsWith('otpauth://', $enroll->json('data.provisioning_uri'));
        $this->assertCount(8, $enroll->json('data.recovery_codes'));

        // Not yet enabled until verified.
        $this->assertFalse($this->makeFresh()->mfa_enabled);

        // Verify with a valid TOTP → enabled.
        $this->withToken($token)->postJson('/api/v1/auth/mfa/verify', ['code' => $this->totp($secret)])
            ->assertOk()->assertJsonPath('data.mfa_enabled', true);
        Event::assertDispatched(MfaEnrolled::class);
        $this->assertTrue($this->makeFresh()->mfa_enabled);

        // Next login requires an MFA challenge (no full token yet).
        $this->app['auth']->forgetGuards();
        $challenge = $this->login()->assertOk()
            ->assertJsonPath('data.mfa_required', true)
            ->assertJsonMissingPath('data.token');
        $mfaToken = $challenge->json('data.mfa_token');

        // Pass the challenge → full token + user.
        $this->app['auth']->forgetGuards();
        $full = $this->withToken($mfaToken)->postJson('/api/v1/auth/mfa/challenge', ['code' => $this->totp($secret)])
            ->assertOk()->assertJsonPath('data.user.email', 'user@example.test');
        $this->assertNotNull($full->json('data.token'));
    }

    public function test_recovery_code_works_once(): void
    {
        $user = $this->makeUser();
        [$secret, $codes] = $this->enableMfaDirectly($user);

        // Login → challenge token.
        $mfaToken = $this->login()->json('data.mfa_token');

        // Use a recovery code to pass the challenge.
        $this->app['auth']->forgetGuards();
        $this->withToken($mfaToken)->postJson('/api/v1/auth/mfa/challenge', ['code' => $codes[0]])
            ->assertOk()->assertJsonStructure(['data' => ['token', 'user']]);

        // Same recovery code cannot be reused.
        $this->app['auth']->forgetGuards();
        $mfaToken2 = $this->login()->json('data.mfa_token');
        $this->app['auth']->forgetGuards();
        $this->withToken($mfaToken2)->postJson('/api/v1/auth/mfa/challenge', ['code' => $codes[0]])
            ->assertStatus(401)->assertJsonPath('error.code', 'MFA_FAILED');
    }

    public function test_admin_cannot_complete_login_without_mfa(): void
    {
        $this->makeUser(RoleKey::SystemAdministrator);

        // Login returns a setup requirement, NOT a full token.
        $login = $this->login()->assertOk()
            ->assertJsonPath('data.mfa_setup_required', true)
            ->assertJsonMissingPath('data.token');
        $setupToken = $login->json('data.mfa_token');

        // The setup token cannot reach a full-token endpoint.
        $this->app['auth']->forgetGuards();
        $this->withToken($setupToken)->getJson('/api/v1/auth/me')->assertStatus(403);

        // Enroll + verify using the setup token → promoted to a full session.
        $this->app['auth']->forgetGuards();
        $secret = $this->withToken($setupToken)->postJson('/api/v1/auth/mfa/enroll')->assertOk()->json('data.secret');

        $this->app['auth']->forgetGuards();
        $full = $this->withToken($setupToken)->postJson('/api/v1/auth/mfa/verify', ['code' => $this->totp($secret)])
            ->assertOk();
        $fullToken = $full->json('data.token');
        $this->assertNotNull($fullToken);

        // Full token now works on /me.
        $this->app['auth']->forgetGuards();
        $this->withToken($fullToken)->getJson('/api/v1/auth/me')->assertOk()
            ->assertJsonPath('data.role.key', RoleKey::SystemAdministrator->value);
    }

    public function test_invalid_totp_on_challenge_is_rejected(): void
    {
        Event::fake([MfaChallengeFailed::class]);
        $user = $this->makeUser();
        $this->enableMfaDirectly($user);

        $mfaToken = $this->login()->json('data.mfa_token');

        $this->app['auth']->forgetGuards();
        $this->withToken($mfaToken)->postJson('/api/v1/auth/mfa/challenge', ['code' => '000000'])
            ->assertStatus(401)->assertJsonPath('error.code', 'MFA_FAILED');

        Event::assertDispatched(MfaChallengeFailed::class);
    }

    public function test_required_role_cannot_disable_mfa(): void
    {
        $admin = $this->makeUser(RoleKey::SystemAdministrator);
        [$secret] = $this->enableMfaDirectly($admin);
        $token = $admin->createToken('test')->plainTextToken;

        $this->withToken($token)->postJson('/api/v1/auth/mfa/disable', ['code' => $this->totp($secret)])
            ->assertStatus(403)->assertJsonPath('error.code', 'MFA_REQUIRED');
    }

    public function test_optional_user_can_disable_mfa_with_valid_code(): void
    {
        $user = $this->makeUser();
        [$secret] = $this->enableMfaDirectly($user);
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)->postJson('/api/v1/auth/mfa/disable', ['code' => $this->totp($secret)])
            ->assertOk()->assertJsonPath('data.mfa_enabled', false);

        $this->assertFalse($this->makeFresh()->mfa_enabled);
        $this->assertNull($this->makeFresh()->mfa_secret);
    }

    public function test_mfa_challenge_failures_lock_the_account(): void
    {
        $user = $this->makeUser();
        $this->enableMfaDirectly($user);
        $mfaToken = $this->login()->json('data.mfa_token');

        // Attempts below the threshold fail with MFA_FAILED...
        for ($i = 0; $i < 4; $i++) {
            $this->app['auth']->forgetGuards();
            $this->withToken($mfaToken)->postJson('/api/v1/auth/mfa/challenge', ['code' => '000000'])
                ->assertStatus(401)->assertJsonPath('error.code', 'MFA_FAILED');
        }

        // ...the 5th trips the account lock.
        $this->app['auth']->forgetGuards();
        $this->withToken($mfaToken)->postJson('/api/v1/auth/mfa/challenge', ['code' => '000000'])
            ->assertStatus(423)->assertJsonPath('error.code', 'ACCOUNT_LOCKED');

        $this->assertTrue($this->makeFresh()->isLocked());
    }

    /**
     * Enable MFA directly (bypassing the endpoints) for setup of other tests.
     *
     * @return array{0: string, 1: list<string>}
     */
    private function enableMfaDirectly(User $user): array
    {
        $secret = (new Google2FA)->generateSecretKey();
        $codes = ['AAAAA-BBBBB', 'CCCCC-DDDDD', 'EEEEE-FFFFF'];

        $user->forceFill([
            'mfa_secret' => $secret,
            'mfa_recovery_codes' => $codes,
            'mfa_enabled' => true,
        ])->save();

        return [$secret, $codes];
    }

    private function makeFresh(): User
    {
        return User::where('email', 'user@example.test')->firstOrFail();
    }
}
