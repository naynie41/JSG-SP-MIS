<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Events\AccountLocked;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class LockoutTest extends TestCase
{
    use RefreshDatabase;

    private const PASSWORD = 'Sup3rStr0ng!Pass';

    private function makeUser(): User
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $role = Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail();

        return User::factory()->create([
            'email' => 'user@example.test',
            'password' => self::PASSWORD,
            'mda_id' => Mda::factory()->create()->id,
            'role_id' => $role->id,
        ]);
    }

    /**
     * Attempt a login from a distinct IP each time so the per-IP login throttle
     * does not interfere with testing account-level lockout.
     */
    private function attempt(string $password, int $i): TestResponse
    {
        return $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.'.$i])
            ->postJson('/api/v1/auth/login', [
                'email' => 'user@example.test',
                'password' => $password,
            ]);
    }

    private function fresh(): User
    {
        return User::where('email', 'user@example.test')->firstOrFail();
    }

    public function test_account_locks_after_max_failed_logins(): void
    {
        Event::fake([AccountLocked::class]);
        $this->makeUser();

        // Attempts below the threshold return the generic error.
        for ($i = 1; $i <= 4; $i++) {
            $this->attempt('wrong-password', $i)->assertStatus(401)
                ->assertJsonPath('error.code', 'INVALID_CREDENTIALS');
        }

        // The attempt that trips the lock reports the temporary lock + Retry-After.
        $this->attempt('wrong-password', 5)->assertStatus(423)
            ->assertJsonPath('error.code', 'ACCOUNT_LOCKED')
            ->assertHeader('Retry-After');

        $user = $this->fresh();
        $this->assertSame(5, $user->failed_login_attempts);
        $this->assertTrue($user->isLocked());
        Event::assertDispatched(AccountLocked::class);

        // Even the correct password is rejected while locked.
        $this->attempt(self::PASSWORD, 99)->assertStatus(423)
            ->assertJsonPath('error.code', 'ACCOUNT_LOCKED');
    }

    public function test_lockout_clears_after_expiry(): void
    {
        $this->makeUser();

        for ($i = 1; $i <= 5; $i++) {
            $this->attempt('wrong-password', $i);
        }
        $this->assertTrue($this->fresh()->isLocked());

        // Travel past the lock window (default decay 1 minute).
        $this->travel(2)->minutes();

        $this->attempt(self::PASSWORD, 50)->assertOk()
            ->assertJsonStructure(['data' => ['token', 'user']]);

        $user = $this->fresh();
        $this->assertSame(0, $user->failed_login_attempts);
        $this->assertNull($user->locked_until);
    }

    public function test_failed_attempts_reset_on_successful_login(): void
    {
        $this->makeUser();

        for ($i = 1; $i <= 3; $i++) {
            $this->attempt('wrong-password', $i)->assertStatus(401);
        }
        $this->assertSame(3, $this->fresh()->failed_login_attempts);
        $this->assertFalse($this->fresh()->isLocked());

        $this->attempt(self::PASSWORD, 50)->assertOk();
        $this->assertSame(0, $this->fresh()->failed_login_attempts);
    }

    /* ------------------------------------------------ administrator unlock */

    private function administrator(): User
    {
        return User::factory()->create([
            'role_id' => Role::where('key', RoleKey::SystemAdministrator->value)->firstOrFail()->id,
        ]);
    }

    private function lockOut(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->attempt('wrong-password', $i);
        }

        $this->assertTrue($this->fresh()->isLocked(), 'Setup failed: the account should be locked by now.');
    }

    /**
     * An administrator can lift a lockout (FR-UAM-06).
     *
     * The backoff is exponential and capped in hours, so without this a user who
     * mistyped their password five times could be shut out for the rest of a working
     * day while an administrator watched a "locked" badge and held no control that
     * would help — the console showed the state and offered nothing to do about it.
     */
    public function test_an_administrator_can_unlock_a_locked_account(): void
    {
        $user = $this->makeUser();
        $this->lockOut();

        $this->withToken($this->administrator()->createToken('t')->plainTextToken)
            ->postJson("/api/v1/users/{$user->id}/unlock")
            ->assertOk();

        $fresh = $this->fresh();
        $this->assertFalse($fresh->isLocked());
        $this->assertNull($fresh->locked_until);
        $this->assertSame(0, $fresh->failed_login_attempts);

        // The point of the whole exercise: they can sign in again straight away.
        $this->attempt(self::PASSWORD, 60)->assertOk();
    }

    /**
     * Status and lockout are independent. Clearing a lock as a side effect of a status
     * change would mean an administrator reactivating a suspended account silently
     * undid a live brute-force defence.
     */
    public function test_activating_a_user_does_not_clear_a_lockout(): void
    {
        $user = $this->makeUser();
        $this->lockOut();

        $this->withToken($this->administrator()->createToken('t')->plainTextToken)
            ->postJson("/api/v1/users/{$user->id}/activate")
            ->assertOk();

        $this->assertTrue($this->fresh()->isLocked(), 'Activate must not lift a brute-force lock as a side effect.');
    }

    public function test_unlocking_needs_the_user_edit_permission(): void
    {
        $user = $this->makeUser();

        $this->postJson("/api/v1/users/{$user->id}/unlock")->assertUnauthorized();

        $executive = User::factory()->create([
            'role_id' => Role::where('key', RoleKey::Executive->value)->firstOrFail()->id,
        ]);

        $this->withToken($executive->createToken('t')->plainTextToken)
            ->postJson("/api/v1/users/{$user->id}/unlock")
            ->assertForbidden();
    }

    /** Unlocking an account that was never locked is harmless and says so. */
    public function test_unlocking_an_unlocked_account_is_a_no_op(): void
    {
        $user = $this->makeUser();

        $this->withToken($this->administrator()->createToken('t')->plainTextToken)
            ->postJson("/api/v1/users/{$user->id}/unlock")
            ->assertOk()
            ->assertJsonPath('data.message', 'The account was not locked. Any failed sign-in attempts have been cleared.');
    }
}
