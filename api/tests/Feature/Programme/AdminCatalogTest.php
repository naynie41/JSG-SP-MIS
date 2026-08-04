<?php

declare(strict_types=1);

namespace Tests\Feature\Programme;

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
 * Programme Catalog section of the administration console. The catalog stays GLOBAL and
 * unowned (CLAUDE.md §10): only catalog administrators write to it, MDAs never can, and
 * the console composes the existing `/programmes` endpoints rather than adding a second
 * catalog. Adds cross-MDA USAGE (`mdas_count`) alongside the activity count that already
 * existed — both derived from the same `activities` relation, so they inherit the same
 * scoping rules.
 */
class AdminCatalogTest extends TestCase
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

        $this->health = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->women = Mda::factory()->create(['name' => 'Women Affairs']);

        $this->users['admin'] = $this->user(null, RoleKey::SystemAdministrator);
        $this->users['coordination'] = $this->user(null, RoleKey::SpCoordination);
        $this->users['officer'] = $this->user($this->health, RoleKey::MdaOfficer);
        $this->users['mdaAdmin'] = $this->user($this->health, RoleKey::MdaAdmin);
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

    /** @return array<string, mixed> */
    private function catalogPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Conditional Cash Transfer',
            'objective' => 'Reduce household poverty',
            'type' => 'individual',
            'benefit_category' => 'cash',
            // Standard eligibility is a list of {attribute, value} criteria (§10).
            'eligibility' => [['attribute' => 'age_min', 'value' => 18]],
            'enforce_eligibility' => true,
            'status' => 'active',
        ], $overrides);
    }

    /* --------------------------------------- admin-only writes (catalog is global) */

    public function test_a_system_administrator_manages_the_global_catalog(): void
    {
        $created = $this->as('admin', 'POST', '/api/v1/programmes', $this->catalogPayload())
            ->assertCreated()->json('data');

        // Every catalog attribute the console surfaces round-trips.
        $this->assertSame('Conditional Cash Transfer', $created['name']);
        $this->assertSame('individual', $created['type']);          // programme category
        $this->assertSame('cash', $created['benefit_category']);    // benefit category
        $this->assertSame([['attribute' => 'age_min', 'value' => 18]], $created['eligibility']);
        $this->assertTrue($created['enforce_eligibility']);
        $this->assertSame('active', $created['status']);

        // Edit + archive run through the same existing endpoints.
        $this->as('admin', 'PATCH', "/api/v1/programmes/{$created['id']}", ['benefit_category' => 'food'])
            ->assertOk()->assertJsonPath('data.benefit_category', 'food');
        $this->as('admin', 'POST', "/api/v1/programmes/{$created['id']}/archive")
            ->assertOk()->assertJsonPath('data.status', 'archived');
    }

    public function test_mdas_can_never_create_or_edit_catalog_programmes(): void
    {
        $programme = Programme::factory()->individual()->create(['status' => 'active']);

        // Neither an MDA officer nor an MDA administrator may write to the catalog —
        // it is global and unowned (§10). They may still READ it to select a programme.
        foreach (['officer', 'mdaAdmin'] as $key) {
            $this->as($key, 'POST', '/api/v1/programmes', $this->catalogPayload())->assertStatus(403);
            $this->as($key, 'PATCH', "/api/v1/programmes/{$programme->id}", ['name' => 'Renamed'])->assertStatus(403);
            $this->as($key, 'POST', "/api/v1/programmes/{$programme->id}/archive")->assertStatus(403);

            $this->as($key, 'GET', '/api/v1/programmes')->assertOk();
        }

        $this->assertSame('active', $programme->fresh()->status->value);
    }

    public function test_sp_coordination_co_administers_the_catalog(): void
    {
        // §10: the catalog is administered centrally — SP Coordination alongside the
        // System Administrator. The console does not change that policy.
        $this->as('coordination', 'POST', '/api/v1/programmes', $this->catalogPayload(['name' => 'School Feeding']))
            ->assertCreated();
    }

    /* --------------------------------------------------------- cross-MDA usage */

    public function test_usage_reports_activities_and_distinct_mdas_per_programme(): void
    {
        $shared = Programme::factory()->individual()->create(['name' => 'Shared Programme', 'status' => 'active']);
        $solo = Programme::factory()->individual()->create(['name' => 'Solo Programme', 'status' => 'active']);
        $unused = Programme::factory()->individual()->create(['name' => 'Unused Programme', 'status' => 'draft']);

        // Shared: 3 activities across 2 MDAs. Solo: 1 activity in 1 MDA. Unused: none.
        Activity::factory()->forProgramme($shared, $this->health)->create();
        Activity::factory()->forProgramme($shared, $this->health)->create();
        Activity::factory()->forProgramme($shared, $this->women)->create();
        Activity::factory()->forProgramme($solo, $this->women)->create();

        $rows = collect($this->as('admin', 'GET', '/api/v1/programmes?per_page=100')->assertOk()->json('data'))
            ->keyBy('name');

        $this->assertSame(3, $rows['Shared Programme']['activities_count']);
        $this->assertSame(2, $rows['Shared Programme']['mdas_count']);   // ONE programme, many MDAs
        $this->assertSame(1, $rows['Solo Programme']['activities_count']);
        $this->assertSame(1, $rows['Solo Programme']['mdas_count']);
        $this->assertSame(0, $rows['Unused Programme']['activities_count']);
        $this->assertSame(0, $rows['Unused Programme']['mdas_count']);
    }

    public function test_usage_is_also_available_on_a_single_programme(): void
    {
        $programme = Programme::factory()->individual()->create(['status' => 'active']);
        Activity::factory()->forProgramme($programme, $this->health)->create();
        Activity::factory()->forProgramme($programme, $this->women)->create();

        $this->as('admin', 'GET', "/api/v1/programmes/{$programme->id}")->assertOk()
            ->assertJsonPath('data.activities_count', 2)
            ->assertJsonPath('data.mdas_count', 2);
    }

    public function test_usage_follows_the_callers_existing_mda_scope(): void
    {
        $programme = Programme::factory()->individual()->create(['name' => 'Scoped Programme', 'status' => 'active']);
        Activity::factory()->forProgramme($programme, $this->health)->create();
        Activity::factory()->forProgramme($programme, $this->women)->create();

        // The administrator holds cross-mda.view, so usage is platform-wide.
        $adminRow = collect($this->as('admin', 'GET', '/api/v1/programmes?per_page=100')->json('data'))
            ->firstWhere('name', 'Scoped Programme');
        $this->assertSame(2, $adminRow['activities_count']);
        $this->assertSame(2, $adminRow['mdas_count']);

        // An MDA officer sees only their own take-up — the same scoping that already
        // governed activities_count, inherited rather than redefined.
        $officerRow = collect($this->as('officer', 'GET', '/api/v1/programmes?per_page=100')->json('data'))
            ->firstWhere('name', 'Scoped Programme');
        $this->assertSame(1, $officerRow['activities_count']);
        $this->assertSame(1, $officerRow['mdas_count']);
    }

    public function test_programmes_remain_global_and_unowned(): void
    {
        $programme = Programme::factory()->individual()->create(['status' => 'active']);

        // The catalog carries no owning MDA — every MDA can read it to build activities.
        $body = $this->as('officer', 'GET', "/api/v1/programmes/{$programme->id}")->assertOk()->json('data');
        $this->assertArrayNotHasKey('owner_mda_id', $body);
        $this->assertArrayNotHasKey('mda', $body);
    }
}
