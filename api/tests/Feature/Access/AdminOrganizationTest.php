<?php

declare(strict_types=1);

namespace Tests\Feature\Access;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Organization section of the administration console. The roll-up is READ ONLY over
 * existing data (Phase 1 MDAs/users + Phase 4 activities + Phase 6P funding attribution);
 * organizations are still MANAGED through the existing `/mdas` endpoints and policies.
 * These tests cover that reuse, the roll-up's accuracy, and the role gate.
 */
class AdminOrganizationTest extends TestCase
{
    use RefreshDatabase;

    private Mda $health;

    private Mda $women;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->health = Mda::factory()->create(['name' => 'Ministry of Health', 'status' => 'active']);
        $this->women = Mda::factory()->create(['name' => 'Women Affairs', 'status' => 'inactive']);

        $this->users['admin'] = $this->user(null, RoleKey::SystemAdministrator);
        $this->users['officer'] = $this->user($this->health, RoleKey::MdaOfficer);
        $this->users['coordination'] = $this->user(null, RoleKey::SpCoordination);

        // Health: 1 officer + 2 MDA admins = 3 users; Women: 1 officer.
        $this->user($this->health, RoleKey::MdaAdmin);
        $this->user($this->health, RoleKey::MdaAdmin);
        $this->user($this->women, RoleKey::MdaOfficer);

        // Activities: Health owns 2 (1 active, 1 archived); Women owns 1 active.
        $programme = Programme::factory()->individual()->create(['status' => 'active']);
        $this->users['partner'] = $this->user(null, RoleKey::DevelopmentPartner);

        Activity::factory()->forProgramme($programme, $this->health)->create([
            'status' => 'active', 'funding_partner_id' => $this->users['partner']->id,
        ]);
        Activity::factory()->forProgramme($programme, $this->health)->create(['status' => 'archived']);
        Activity::factory()->forProgramme($programme, $this->women)->create([
            'status' => 'active', 'funding_partner_id' => $this->users['partner']->id,
        ]);
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

    /* ------------------------------------------------------------------ gating */

    public function test_the_roll_up_is_gated_to_the_system_administrator(): void
    {
        $this->as('admin', 'GET', '/api/v1/admin/organizations')->assertOk();

        foreach (['officer', 'coordination'] as $key) {
            $this->as($key, 'GET', '/api/v1/admin/organizations')
                ->assertStatus(403)->assertJsonPath('error.code', 'FORBIDDEN');
        }
    }

    /* ------------------------------------------------ management stays on /mdas */

    public function test_organizations_are_managed_through_the_existing_mda_endpoints(): void
    {
        // Create / edit / deactivate / activate all run through Phase 1, not the console.
        $created = $this->as('admin', 'POST', '/api/v1/mdas', [
            'name' => 'Ministry of Education',
            'type' => 'ministry',
            'contact_email' => 'edu@example.test',
        ])->assertCreated()->json('data');

        $this->as('admin', 'PATCH', "/api/v1/mdas/{$created['id']}", ['name' => 'Ministry of Basic Education'])
            ->assertOk()->assertJsonPath('data.name', 'Ministry of Basic Education');

        $this->as('admin', 'POST', "/api/v1/mdas/{$created['id']}/deactivate")
            ->assertOk()->assertJsonPath('data.status', 'inactive');
        $this->as('admin', 'POST', "/api/v1/mdas/{$created['id']}/activate")
            ->assertOk()->assertJsonPath('data.status', 'active');

        // The new organization immediately appears in the roll-up — one source of truth.
        $names = array_column($this->as('admin', 'GET', '/api/v1/admin/organizations')->json('data.mdas'), 'name');
        $this->assertContains('Ministry of Basic Education', $names);
    }

    public function test_phase_1_mda_permission_gating_still_applies(): void
    {
        $this->as('officer', 'POST', '/api/v1/mdas', ['name' => 'X', 'type' => 'ministry'])->assertStatus(403);
        $this->as('officer', 'POST', "/api/v1/mdas/{$this->health->id}/deactivate")->assertStatus(403);
    }

    /* ------------------------------------------------------------------ roll-up */

    public function test_user_allocation_and_activities_are_reported_per_organization(): void
    {
        $data = $this->as('admin', 'GET', '/api/v1/admin/organizations')->assertOk()->json('data');
        $byName = collect($data['mdas'])->keyBy('name');

        $health = $byName['Ministry of Health'];
        $this->assertSame('active', $health['status']);
        $this->assertSame(3, $health['users_total']);        // officer + 2 MDA admins
        $this->assertSame(2, $health['mda_admins']);
        $this->assertSame(2, $health['activities_total']);   // active + archived
        $this->assertSame(1, $health['activities_active']);

        $women = $byName['Women Affairs'];
        $this->assertSame('inactive', $women['status']);
        $this->assertSame(1, $women['users_total']);
        $this->assertSame(0, $women['mda_admins']);
        $this->assertSame(1, $women['activities_total']);

        // Totals reconcile: allocated + unallocated == every user on the platform.
        $this->assertSame(2, $data['totals']['mdas']);
        $this->assertSame(1, $data['totals']['mdas_active']);
        $this->assertSame(
            User::query()->count(),
            $data['totals']['users_allocated'] + $data['totals']['users_unallocated'],
        );
    }

    public function test_development_partners_report_the_delivery_they_fund(): void
    {
        $partners = collect($this->as('admin', 'GET', '/api/v1/admin/organizations')->json('data.partners'));
        $partner = $partners->firstWhere('id', $this->users['partner']->id);

        $this->assertNotNull($partner);
        $this->assertSame('active', $partner['status']);
        $this->assertSame(2, $partner['funded_activities']);   // one in each MDA
        $this->assertSame(1, $partner['funded_programmes']);
        $this->assertSame(2, $partner['implementing_mdas']);
    }

    public function test_the_roll_up_is_platform_wide_not_mda_scoped(): void
    {
        // Activities owned by an MDA the caller has no membership of still count — the
        // administrator's remit is the whole platform.
        $other = Mda::factory()->create(['name' => 'Zzz Agency']);
        $programme = Programme::factory()->individual()->create(['status' => 'active']);
        Activity::factory()->forProgramme($programme, $other)->create(['status' => 'active']);

        $row = collect($this->as('admin', 'GET', '/api/v1/admin/organizations')->json('data.mdas'))
            ->firstWhere('name', 'Zzz Agency');

        $this->assertSame(1, $row['activities_total']);
        $this->assertSame(1, $row['activities_active']);
    }
}
