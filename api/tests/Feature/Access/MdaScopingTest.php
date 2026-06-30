<?php

declare(strict_types=1);

namespace Tests\Feature\Access;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Events\CrossMdaAccessGranted;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\MdaAccessGrant;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MdaScopingTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private User $userA;   // belongs to MDA A

    private User $userB;   // belongs to MDA B

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $officer = Role::where('key', RoleKey::MdaOfficer->value)->firstOrFail()->id;
        $this->userA = User::factory()->create(['email' => 'a@example.test', 'mda_id' => $this->mdaA->id, 'role_id' => $officer]);
        $this->userB = User::factory()->create(['email' => 'b@example.test', 'mda_id' => $this->mdaB->id, 'role_id' => $officer]);
    }

    private function token(User $user): string
    {
        return $user->createToken('test')->plainTextToken;
    }

    public function test_user_only_sees_their_own_mdas_users(): void
    {
        $emails = $this->withToken($this->token($this->userA))
            ->getJson('/api/v1/users')->assertOk()
            ->json('data.users.*.email');

        $this->assertContains('a@example.test', $emails);
        $this->assertNotContains('b@example.test', $emails);
    }

    public function test_central_scope_is_enforced_at_the_query_layer(): void
    {
        Sanctum::actingAs($this->userA);

        // Not just the endpoint — the Eloquent query itself is scoped.
        $this->assertNull(User::find($this->userB->id));
        $this->assertNotNull(User::find($this->userA->id));
        $this->assertSame([$this->mdaA->id], User::query()->get()->pluck('mda_id')->unique()->values()->all());
    }

    public function test_cross_mda_grant_extends_visibility(): void
    {
        MdaAccessGrant::create([
            'user_id' => $this->userA->id,
            'mda_id' => $this->mdaB->id,
            'reason' => 'Coordination',
        ]);

        $emails = $this->withToken($this->token($this->userA))
            ->getJson('/api/v1/users')->assertOk()
            ->json('data.users.*.email');

        $this->assertContains('a@example.test', $emails);
        $this->assertContains('b@example.test', $emails);
    }

    public function test_expired_grant_does_not_extend_visibility(): void
    {
        MdaAccessGrant::create([
            'user_id' => $this->userA->id,
            'mda_id' => $this->mdaB->id,
            'expires_at' => now()->subDay(),
        ]);

        $emails = $this->withToken($this->token($this->userA))
            ->getJson('/api/v1/users')->assertOk()
            ->json('data.users.*.email');

        $this->assertNotContains('b@example.test', $emails);
    }

    public function test_cross_mda_view_permission_bypasses_scoping(): void
    {
        $coordinator = User::factory()->create([
            'mda_id' => $this->mdaA->id,
            'role_id' => Role::where('key', RoleKey::SpCoordination->value)->firstOrFail()->id, // has cross-mda.view
        ]);

        $emails = $this->withToken($this->token($coordinator))
            ->getJson('/api/v1/users')->assertOk()
            ->json('data.users.*.email');

        $this->assertContains('a@example.test', $emails);
        $this->assertContains('b@example.test', $emails);
    }

    public function test_admin_can_grant_cross_mda_access_and_it_takes_effect(): void
    {
        Event::fake([CrossMdaAccessGranted::class]);

        $admin = User::factory()->create([
            'role_id' => Role::where('key', RoleKey::SystemAdministrator->value)->firstOrFail()->id,
        ]);

        // Before the grant, user A cannot see MDA B.
        $this->withToken($this->token($this->userA))->getJson('/api/v1/users')
            ->assertJsonMissing(['email' => 'b@example.test']);
        $this->app['auth']->forgetGuards();

        // Admin grants user A access to MDA B.
        $this->withToken($this->token($admin))->postJson('/api/v1/mda-access-grants', [
            'user_id' => $this->userA->id,
            'mda_id' => $this->mdaB->id,
            'reason' => 'Joint programme',
        ])->assertStatus(201);

        Event::assertDispatched(CrossMdaAccessGranted::class);
        $this->assertDatabaseHas('mda_access_grants', [
            'user_id' => $this->userA->id,
            'mda_id' => $this->mdaB->id,
            'granted_by' => $admin->id,
        ]);

        // Now user A can see MDA B's users.
        $this->app['auth']->forgetGuards();
        $emails = $this->withToken($this->token($this->userA))
            ->getJson('/api/v1/users')->json('data.users.*.email');
        $this->assertContains('b@example.test', $emails);
    }

    public function test_grant_management_requires_permission(): void
    {
        // An MDA Officer lacks mda-access.create.
        $this->withToken($this->token($this->userA))->postJson('/api/v1/mda-access-grants', [
            'user_id' => $this->userB->id,
            'mda_id' => $this->mdaA->id,
        ])->assertStatus(403)->assertJsonPath('error.code', 'FORBIDDEN');
    }

    public function test_revoking_a_grant_removes_visibility(): void
    {
        $grant = MdaAccessGrant::create([
            'user_id' => $this->userA->id,
            'mda_id' => $this->mdaB->id,
        ]);

        $admin = User::factory()->create([
            'role_id' => Role::where('key', RoleKey::SystemAdministrator->value)->firstOrFail()->id,
        ]);

        $this->withToken($this->token($admin))
            ->deleteJson("/api/v1/mda-access-grants/{$grant->id}")->assertOk();

        $this->assertDatabaseMissing('mda_access_grants', ['id' => $grant->id]);

        $this->app['auth']->forgetGuards();
        $emails = $this->withToken($this->token($this->userA))
            ->getJson('/api/v1/users')->json('data.users.*.email');
        $this->assertNotContains('b@example.test', $emails);
    }
}
