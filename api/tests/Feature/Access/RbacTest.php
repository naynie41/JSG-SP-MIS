<?php

declare(strict_types=1);

namespace Tests\Feature\Access;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Permission;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Expected permission bundles for each role (FR-UAM-01).
     *
     * @var array<string, list<string>>
     */
    private const EXPECTED = [
        'sp_coordination' => [
            'cross-mda.view', 'mda.view', 'user.view', 'role.view', 'permission.view',
            'beneficiary.view', 'beneficiary.export', 'beneficiary-lookup.view',
        ],
        'mne_officer' => [
            'cross-mda.view', 'mda.view', 'user.view',
            'beneficiary.view', 'beneficiary.export', 'beneficiary-lookup.view',
        ],
        'mda_admin' => [
            'mda.view', 'user.view', 'user.create', 'user.edit', 'role.view',
            'beneficiary.view', 'beneficiary.create', 'beneficiary.edit', 'beneficiary.approve',
            'beneficiary-lookup.view', 'household.view', 'household.create', 'household.edit',
        ],
        'mda_officer' => [
            'mda.view', 'user.view',
            'beneficiary.view', 'beneficiary.create', 'beneficiary.edit',
            'beneficiary-lookup.view', 'household.view', 'household.create', 'household.edit',
        ],
        'development_partner' => ['mda.view', 'beneficiary.view'],
        'executive' => [
            'cross-mda.view', 'mda.view', 'user.view',
            'beneficiary.view', 'beneficiary.export', 'beneficiary-lookup.view',
        ],
    ];

    /** @var array<string, User> one user per role key, plus 'none' */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        // Create all users up front (before any request). Creating an Auditable
        // model between sub-requests would resolve Auth::user() against the
        // previous request and cache a stale user, breaking later auth in the
        // same test. Building them here avoids that entirely.
        foreach (RoleKey::cases() as $roleKey) {
            $this->users[$roleKey->value] = User::factory()->create([
                'role_id' => Role::where('key', $roleKey->value)->firstOrFail()->id,
                'mda_id' => Mda::factory()->create()->id,
            ]);
        }

        $this->users['none'] = User::factory()->create([
            'role_id' => null,
            'mda_id' => Mda::factory()->create()->id,
        ]);
    }

    private function userWithRole(?RoleKey $roleKey): User
    {
        return $this->users[$roleKey?->value ?? 'none'];
    }

    private function tokenFor(?RoleKey $roleKey): string
    {
        // Only mints a token (no Auditable model creation) — safe between requests.
        return $this->userWithRole($roleKey)->createToken('test')->plainTextToken;
    }

    public function test_user_with_required_permission_is_allowed(): void
    {
        // System Administrator and SP Coordination both hold permission.view.
        foreach ([RoleKey::SystemAdministrator, RoleKey::SpCoordination] as $roleKey) {
            $token = $this->tokenFor($roleKey);
            $this->withToken($token)->getJson('/api/v1/permissions')
                ->assertOk()
                ->assertJsonStructure(['data' => ['modules']]);
            $this->app['auth']->forgetGuards();
        }
    }

    public function test_user_without_permission_is_denied_with_403_envelope(): void
    {
        $token = $this->tokenFor(RoleKey::MdaOfficer); // lacks permission.view

        $this->withToken($token)->getJson('/api/v1/permissions')
            ->assertStatus(403)
            ->assertJsonPath('error.code', 'FORBIDDEN')
            ->assertJsonStructure(['error' => ['code', 'message', 'details']]);
    }

    public function test_roles_endpoint_enforces_role_view_permission(): void
    {
        // mda_admin has role.view → allowed.
        $token = $this->tokenFor(RoleKey::MdaAdmin);
        $this->withToken($token)->getJson('/api/v1/roles')->assertOk();

        // development_partner lacks role.view → denied.
        $this->app['auth']->forgetGuards();
        $token = $this->tokenFor(RoleKey::DevelopmentPartner);
        $this->withToken($token)->getJson('/api/v1/roles')->assertStatus(403);
    }

    public function test_user_without_a_role_is_denied_by_default(): void
    {
        $token = $this->tokenFor(null); // no role → no permissions

        $this->withToken($token)->getJson('/api/v1/permissions')->assertStatus(403);
        $this->app['auth']->forgetGuards();
        $this->withToken($token)->getJson('/api/v1/roles')->assertStatus(403);
    }

    public function test_matrix_endpoint_returns_roles_and_permissions(): void
    {
        $token = $this->tokenFor(RoleKey::SystemAdministrator);

        $this->withToken($token)->getJson('/api/v1/access/matrix')
            ->assertOk()
            ->assertJsonStructure(['data' => ['permissions', 'roles' => [['key', 'name', 'permissions']]]]);
    }

    public function test_seven_roles_resolve_to_correct_permission_sets(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->assertCount(7, Role::all());

        $allKeys = Permission::pluck('key')->sort()->values()->all();

        foreach (Role::with('permissions')->get() as $role) {
            $actual = $role->permissions->pluck('key')->sort()->values()->all();

            if ($role->key === RoleKey::SystemAdministrator->value) {
                $this->assertSame($allKeys, $actual, 'System Administrator should hold every permission');

                continue;
            }

            $expected = collect(self::EXPECTED[$role->key])->sort()->values()->all();
            $this->assertSame($expected, $actual, "Permission set mismatch for role {$role->key}");
        }
    }

    public function test_gate_resolves_module_action_abilities(): void
    {
        $officer = $this->userWithRole(RoleKey::MdaOfficer);

        $this->assertTrue($officer->can('mda.view'));
        $this->assertTrue($officer->can('user.view'));
        $this->assertFalse($officer->can('permission.view'));
        $this->assertFalse($officer->can('user.create'));
    }
}
