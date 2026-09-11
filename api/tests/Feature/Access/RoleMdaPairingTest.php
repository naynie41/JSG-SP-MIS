<?php

declare(strict_types=1);

namespace Tests\Feature\Access;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * An MDA-scoped role must have an MDA; a state-level role must not (FR-UAM-02/03).
 *
 * Before this the requirement was read off the ACTOR — `mda_id` was required unless
 * the actor held cross-mda.view — which meant a System Administrator could create an
 * MDA Admin with no MDA at all, and could pin an Executive to one. The rule belongs
 * to the role being assigned.
 */
class RoleMdaPairingTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Mda $mda;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        Http::fake(['*' => Http::response('', 200)]);

        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->admin = User::factory()->create([
            'role_id' => $this->roleId(RoleKey::SystemAdministrator),
        ]);
    }

    private function roleId(RoleKey $key): string
    {
        return Role::where('key', $key->value)->firstOrFail()->id;
    }

    private function token(): string
    {
        return $this->admin->createToken('test')->plainTextToken;
    }

    /** @param array<string, mixed> $overrides */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'New User',
            'email' => 'new.user@example.test',
            'password' => 'Sup3rStr0ng!Pass',
            'password_confirmation' => 'Sup3rStr0ng!Pass',
        ], $overrides);
    }

    // ---------------------------------------------------------------- the flag

    public function test_only_mda_admin_is_marked_mda_scoped(): void
    {
        $scoped = Role::where('requires_mda', true)->pluck('key')->all();

        // M&E Officer is state-level despite sounding operational — it is not in the
        // frontend's MDA_ROLES either, and the two must agree.
        $this->assertSame([RoleKey::MdaAdmin->value], $scoped);
    }

    public function test_the_roles_endpoint_exposes_the_flag(): void
    {
        // The user form drives its MDA dropdown off this, rather than keeping a
        // second list of MDA-scoped roles that could drift from the server's.
        $roles = $this->withToken($this->token())->getJson('/api/v1/roles')->assertOk()->json('data.roles');

        $mdaAdmin = collect($roles)->firstWhere('key', RoleKey::MdaAdmin->value);
        $executive = collect($roles)->firstWhere('key', RoleKey::Executive->value);

        $this->assertTrue($mdaAdmin['requires_mda']);
        $this->assertFalse($executive['requires_mda']);
    }

    // --------------------------------------------------------------- creating

    public function test_creating_an_mda_admin_without_an_mda_is_rejected(): void
    {
        $this->withToken($this->token())
            ->postJson('/api/v1/users', $this->payload([
                'role_id' => $this->roleId(RoleKey::MdaAdmin),
            ]))
            ->assertStatus(422)
            ->assertJsonPath('error.details.0.field', 'mda_id');

        $this->assertDatabaseMissing('users', ['email' => 'new.user@example.test']);
    }

    public function test_creating_an_mda_admin_with_an_mda_succeeds_and_scopes_the_user(): void
    {
        $this->withToken($this->token())
            ->postJson('/api/v1/users', $this->payload([
                'role_id' => $this->roleId(RoleKey::MdaAdmin),
                'mda_id' => $this->mda->id,
            ]))
            ->assertCreated()
            ->assertJsonPath('data.mda.id', $this->mda->id);

        $created = User::where('email', 'new.user@example.test')->firstOrFail();
        $this->assertSame($this->mda->id, $created->mda_id);
    }

    public function test_creating_a_state_level_role_with_an_mda_is_rejected(): void
    {
        // Pinning an Executive to one MDA would silently narrow everything they are
        // meant to see across.
        $this->withToken($this->token())
            ->postJson('/api/v1/users', $this->payload([
                'role_id' => $this->roleId(RoleKey::Executive),
                'mda_id' => $this->mda->id,
            ]))
            ->assertStatus(422)
            ->assertJsonPath('error.details.0.field', 'mda_id');
    }

    public function test_creating_a_state_level_role_without_an_mda_succeeds(): void
    {
        $this->withToken($this->token())
            ->postJson('/api/v1/users', $this->payload([
                'role_id' => $this->roleId(RoleKey::Executive),
            ]))
            ->assertCreated();

        $this->assertNull(User::where('email', 'new.user@example.test')->firstOrFail()->mda_id);
    }

    // ---------------------------------------------------------------- editing

    public function test_changing_an_mda_admin_to_a_state_level_role_without_clearing_the_mda_is_rejected(): void
    {
        // The update is PARTIAL: role_id changes, mda_id is not sent. Judged on the
        // payload alone this looks fine, and the user would be left an Executive
        // still scoped to one MDA.
        $user = User::factory()->create([
            'role_id' => $this->roleId(RoleKey::MdaAdmin),
            'mda_id' => $this->mda->id,
        ]);

        $this->withToken($this->token())
            ->patchJson("/api/v1/users/{$user->id}", [
                'role_id' => $this->roleId(RoleKey::Executive),
            ])
            ->assertStatus(422)
            ->assertJsonPath('error.details.0.field', 'mda_id');

        $this->assertSame($this->mda->id, $user->fresh()->mda_id);
    }

    public function test_changing_to_a_state_level_role_and_clearing_the_mda_succeeds(): void
    {
        $user = User::factory()->create([
            'role_id' => $this->roleId(RoleKey::MdaAdmin),
            'mda_id' => $this->mda->id,
        ]);

        $this->withToken($this->token())
            ->patchJson("/api/v1/users/{$user->id}", [
                'role_id' => $this->roleId(RoleKey::Executive),
                'mda_id' => null,
            ])
            ->assertOk();

        $this->assertNull($user->fresh()->mda_id);
    }

    public function test_changing_to_mda_admin_without_an_mda_is_rejected(): void
    {
        $user = User::factory()->create([
            'role_id' => $this->roleId(RoleKey::Executive),
            'mda_id' => null,
        ]);

        $this->withToken($this->token())
            ->patchJson("/api/v1/users/{$user->id}", [
                'role_id' => $this->roleId(RoleKey::MdaAdmin),
            ])
            ->assertStatus(422);
    }

    public function test_an_unrelated_edit_to_a_valid_user_still_passes(): void
    {
        // The effective-pairing check must not turn every partial edit into a
        // re-validation failure.
        $user = User::factory()->create([
            'role_id' => $this->roleId(RoleKey::MdaAdmin),
            'mda_id' => $this->mda->id,
        ]);

        $this->withToken($this->token())
            ->patchJson("/api/v1/users/{$user->id}", ['name' => 'Renamed Person'])
            ->assertOk();

        $this->assertSame('Renamed Person', $user->fresh()->name);
        $this->assertSame($this->mda->id, $user->fresh()->mda_id);
    }

    public function test_the_rejection_names_the_role_so_the_admin_knows_why(): void
    {
        $response = $this->withToken($this->token())
            ->postJson('/api/v1/users', $this->payload([
                'role_id' => $this->roleId(RoleKey::MdaAdmin),
            ]))->assertStatus(422);

        $this->assertStringContainsString(
            'MDA Admin',
            (string) $response->json('error.details.0.message'),
        );
    }
}
