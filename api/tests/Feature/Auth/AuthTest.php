<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Enums\UserStatus;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(array $attributes = []): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $role = Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail();
        $mda = Mda::factory()->create();

        return User::factory()->create(array_merge([
            'email' => 'officer@example.test',
            'password' => 'Sup3rStr0ng!Pass',
            'mda_id' => $mda->id,
            'role_id' => $role->id,
        ], $attributes));
    }

    public function test_seeded_user_can_login_call_me_and_logout(): void
    {
        $user = $this->makeUser();

        $login = $this->postJson('/api/v1/auth/login', [
            'email' => 'officer@example.test',
            'password' => 'Sup3rStr0ng!Pass',
        ]);

        $login->assertOk()
            ->assertJsonPath('data.token_type', 'Bearer')
            ->assertJsonPath('data.user.email', 'officer@example.test')
            ->assertJsonPath('data.user.role.key', RoleKey::MdaOfficer->value)
            ->assertJsonStructure(['data' => ['token', 'user' => ['id', 'mda', 'role', 'permissions']]]);

        $token = $login->json('data.token');

        $me = $this->withToken($token)->getJson('/api/v1/auth/me');
        $me->assertOk()
            ->assertJsonPath('data.email', 'officer@example.test')
            ->assertJsonPath('data.mda.id', $user->mda_id);
        $this->assertContains('mda.view', $me->json('data.permissions'));

        $logout = $this->withToken($token)->postJson('/api/v1/auth/logout');
        $logout->assertOk();

        // Token is now invalid. Forget the resolved guard so the next call
        // re-reads the (now deleted) token, as a fresh HTTP request would.
        $this->app['auth']->forgetGuards();
        $this->withToken($token)->getJson('/api/v1/auth/me')->assertUnauthorized();
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_login_with_wrong_password_returns_generic_error(): void
    {
        $this->makeUser();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'officer@example.test',
            'password' => 'wrong-password-here',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('error.code', 'INVALID_CREDENTIALS')
            ->assertJsonPath('error.message', 'Invalid credentials.');
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_login_with_unknown_email_returns_same_generic_error(): void
    {
        $this->makeUser();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nobody@example.test',
            'password' => 'whatever-password',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('error.code', 'INVALID_CREDENTIALS')
            ->assertJsonPath('error.message', 'Invalid credentials.');
    }

    public function test_suspended_user_cannot_login(): void
    {
        $this->makeUser(['status' => UserStatus::Suspended]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'officer@example.test',
            'password' => 'Sup3rStr0ng!Pass',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('error.code', 'INVALID_CREDENTIALS');
    }

    public function test_me_requires_authentication(): void
    {
        $this->getJson('/api/v1/auth/me')
            ->assertUnauthorized()
            ->assertJsonPath('error.code', 'UNAUTHENTICATED');
    }

    public function test_login_validation_errors_use_standard_format(): void
    {
        $response = $this->postJson('/api/v1/auth/login', ['email' => 'not-an-email']);

        $response->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonStructure(['error' => ['code', 'message', 'details' => [['field', 'message']]]]);
    }

    public function test_login_is_rate_limited_with_generic_error(): void
    {
        $this->makeUser();

        // 5 attempts are allowed per minute; the 6th is throttled.
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/v1/auth/login', [
                'email' => 'officer@example.test',
                'password' => 'wrong-password',
            ])->assertStatus(401);
        }

        $this->postJson('/api/v1/auth/login', [
            'email' => 'officer@example.test',
            'password' => 'wrong-password',
        ])->assertStatus(429)->assertJsonPath('error.code', 'TOO_MANY_REQUESTS');
    }

    public function test_change_password_rejects_weak_password(): void
    {
        $user = $this->makeUser();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->postJson('/api/v1/auth/password', [
            'current_password' => 'Sup3rStr0ng!Pass',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR')
            ->assertJsonStructure(['error' => ['details' => [['field', 'message']]]]);
        $this->assertSame('password', $response->json('error.details.0.field'));
    }

    public function test_change_password_invalidates_existing_tokens(): void
    {
        Http::fake(['*' => Http::response('', 200)]); // uncompromised() => not breached

        $user = $this->makeUser();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->postJson('/api/v1/auth/password', [
            'current_password' => 'Sup3rStr0ng!Pass',
            'password' => 'An0ther!Str0ngPass',
            'password_confirmation' => 'An0ther!Str0ngPass',
        ]);

        $response->assertOk();
        $this->assertDatabaseCount('personal_access_tokens', 0);

        // Old token no longer works (forget the cached guard first).
        $this->app['auth']->forgetGuards();
        $this->withToken($token)->getJson('/api/v1/auth/me')->assertUnauthorized();

        // New password works for login.
        $this->postJson('/api/v1/auth/login', [
            'email' => 'officer@example.test',
            'password' => 'An0ther!Str0ngPass',
        ])->assertOk();
    }

    public function test_change_password_requires_correct_current_password(): void
    {
        Http::fake(['*' => Http::response('', 200)]);

        $user = $this->makeUser();
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)->postJson('/api/v1/auth/password', [
            'current_password' => 'definitely-not-it',
            'password' => 'An0ther!Str0ngPass',
            'password_confirmation' => 'An0ther!Str0ngPass',
        ])->assertStatus(422)->assertJsonPath('error.code', 'VALIDATION_ERROR');
    }
}
