<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use App\Domain\Reporting\Gis\BoundaryLoader;
use App\Domain\Reporting\Gis\GeoBoundary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * GIS coverage (PRD FR-GIS-01): the boundary loader, scope-aware coverage aggregation,
 * the choropleth FeatureCollection when boundaries are loaded, and the graceful
 * ranked-table fallback when they are not.
 */
class GisCoverageTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaAdmin);
        $this->users['exec'] = $this->user(null, RoleKey::Executive);
        $this->users['noRole'] = User::factory()->create(['mda_id' => $this->mdaA->id, 'role_id' => null]);

        $dutse = Beneficiary::factory()->count(2)->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'lga' => 'hadejia']);

        // Detail fixtures for dutse (MDA A): a household, an active activity, and one
        // served beneficiary (net-unique) with delivered value.
        Household::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse']);
        $programme = Programme::factory()->individual()->create(['status' => 'active']);
        $activity = Activity::factory()->forProgramme($programme, $this->mdaA)->inLgaCode('dutse')->create(['status' => 'active', 'budget_amount' => 500_000]);
        Benefit::factory()->create([
            'beneficiary_id' => $dutse->first()->id, 'programme_id' => $programme->id, 'mda_id' => $this->mdaA->id,
            'activity_id' => $activity->id, 'lga' => 'dutse', 'monetary_value' => 300_000, 'status' => 'verified',
        ]);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda?->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function send(string $key, string $method, string $url): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /**
     * @param  list<string>  $names
     * @return array<string, mixed>
     */
    private function featureCollection(array $names): array
    {
        $features = array_map(fn (string $name): array => [
            'type' => 'Feature',
            'properties' => ['name' => $name],
            'geometry' => ['type' => 'Polygon', 'coordinates' => [[[9.0, 11.0], [9.1, 11.0], [9.1, 11.1], [9.0, 11.1], [9.0, 11.0]]]],
        ], $names);

        return ['type' => 'FeatureCollection', 'features' => $features];
    }

    /**
     * @param  list<array<string, mixed>>  $features
     */
    private function feature(array $features, string $code): array
    {
        foreach ($features as $feature) {
            if (($feature['properties']['code'] ?? null) === $code) {
                return $feature;
            }
        }

        return [];
    }

    public function test_loader_imports_boundaries_idempotently(): void
    {
        $loaded = app(BoundaryLoader::class)->load('lga', $this->featureCollection(['Dutse', 'Hadejia']));

        $this->assertSame(2, $loaded);
        $this->assertSame(2, GeoBoundary::query()->where('level', 'lga')->count());
        $this->assertDatabaseHas('geo_boundaries', ['level' => 'lga', 'code' => 'dutse', 'name' => 'Dutse']);

        // Re-loading upserts (no duplicates).
        app(BoundaryLoader::class)->load('lga', $this->featureCollection(['Dutse', 'Hadejia']));
        $this->assertSame(2, GeoBoundary::query()->where('level', 'lga')->count());
    }

    public function test_command_loads_boundaries_from_a_geojson_file(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'geo').'.json';
        file_put_contents($path, json_encode($this->featureCollection(['Dutse'])));

        Artisan::call('gis:load-boundaries', ['level' => 'lga', 'file' => $path]);
        @unlink($path);

        $this->assertDatabaseHas('geo_boundaries', ['level' => 'lga', 'code' => 'dutse']);
    }

    public function test_coverage_degrades_to_a_table_when_no_boundaries(): void
    {
        // No boundaries loaded — the endpoint must still return coverage as a table.
        $body = $this->send('officerA', 'GET', '/api/v1/gis/coverage?level=lga')
            ->assertOk()
            ->assertJsonPath('data.mode', 'table')
            ->assertJsonPath('data.feature_collection', null)
            ->json('data');

        $dutse = collect($body['rows'])->firstWhere('key', 'dutse');
        $this->assertSame(2, $dutse['beneficiary_count']);
    }

    public function test_coverage_renders_a_scoped_choropleth_when_boundaries_loaded(): void
    {
        app(BoundaryLoader::class)->load('lga', $this->featureCollection(['Dutse', 'Hadejia']));

        // MDA A officer: choropleth, but only their own LGA has beneficiaries.
        $a = $this->send('officerA', 'GET', '/api/v1/gis/coverage?level=lga')
            ->assertOk()->assertJsonPath('data.mode', 'choropleth')->json('data.feature_collection.features');
        $this->assertCount(2, $a);
        $this->assertSame(2, $this->feature($a, 'dutse')['properties']['beneficiary_count']);
        $this->assertSame(0, $this->feature($a, 'hadejia')['properties']['beneficiary_count']); // MDA B's — out of scope

        // Executive: state-wide, so MDA B's LGA is populated too.
        $exec = $this->send('exec', 'GET', '/api/v1/gis/coverage?level=lga')->assertOk()->json('data.feature_collection.features');
        $this->assertSame(1, $this->feature($exec, 'hadejia')['properties']['beneficiary_count']);
    }

    public function test_coverage_carries_absolute_bands_and_click_through_detail(): void
    {
        // Small deterministic thresholds so banding is testable.
        config(['reporting.coverage_bands.green_min' => 2, 'reporting.coverage_bands.yellow_min' => 1]);

        $body = $this->send('exec', 'GET', '/api/v1/gis/coverage?level=lga')->assertOk()->json('data');

        $this->assertSame(2, $body['bands']['green_min']);
        $this->assertSame(1, $body['bands']['yellow_min']);

        $dutse = collect($body['rows'])->firstWhere('key', 'dutse');
        $this->assertSame('green', $dutse['band']);          // 2 registered ≥ green_min 2
        $this->assertSame(2, $dutse['beneficiary_count']);   // registered individuals
        $this->assertSame(1, $dutse['households']);
        $this->assertSame(1, $dutse['served']);              // one served (net-unique)
        $this->assertSame(300_000, $dutse['benefit_value']); // budget spent
        $this->assertSame(1, $dutse['active_activities']);
        $this->assertSame(1, $dutse['active_programmes']);
        $this->assertSame(['MDA A'], $dutse['mdas']);

        $hadejia = collect($body['rows'])->firstWhere('key', 'hadejia');
        $this->assertSame('yellow', $hadejia['band']);       // 1 registered → yellow (≥1, <2)
        $this->assertSame(0, $hadejia['served']);
    }

    public function test_uncovered_boundary_is_grey_with_detail_in_feature_properties(): void
    {
        // Kano has a boundary but no coverage → grey; Dutse carries the click-through detail.
        app(BoundaryLoader::class)->load('lga', $this->featureCollection(['Dutse', 'Hadejia', 'Kano']));

        $features = $this->send('exec', 'GET', '/api/v1/gis/coverage?level=lga')
            ->assertOk()->assertJsonPath('data.mode', 'choropleth')->json('data.feature_collection.features');

        $kano = $this->feature($features, 'kano');
        $this->assertSame('grey', $kano['properties']['band']);
        $this->assertSame(0, $kano['properties']['beneficiary_count']);

        $dutse = $this->feature($features, 'dutse');
        $this->assertSame(1, $dutse['properties']['served']);
        $this->assertSame(1, $dutse['properties']['households']);
        $this->assertSame(['MDA A'], $dutse['properties']['mdas']);
    }

    public function test_coverage_requires_dashboard_permission(): void
    {
        $this->send('noRole', 'GET', '/api/v1/gis/coverage')->assertStatus(403);
    }

    public function test_partner_investment_coverage_is_activity_precise_with_funding(): void
    {
        $this->users['partner'] = $this->user(null, RoleKey::DevelopmentPartner);

        $programme = Programme::factory()->individual()->create(['status' => 'active']);
        // The partner FUNDS an activity in Dutse (MDA A). A co-funded activity in the SAME
        // programme (Hadejia, MDA B) is NOT funded by this partner — it must stay out of scope.
        $funded = Activity::factory()->forProgramme($programme, $this->mdaA)->inLgaCode('dutse')->create(['status' => 'active', 'budget_amount' => 2_000_000, 'funding_partner_id' => $this->users['partner']->id]);
        Activity::factory()->forProgramme($programme, $this->mdaB)->inLgaCode('hadejia')->create(['status' => 'active', 'budget_amount' => 9_000_000]);

        $ben = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse']);
        Benefit::factory()->create([
            'beneficiary_id' => $ben->id, 'programme_id' => $programme->id, 'mda_id' => $this->mdaA->id,
            'activity_id' => $funded->id, 'lga' => 'dutse', 'monetary_value' => 400_000, 'status' => 'verified',
        ]);
        $hh = Household::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse']);
        HouseholdMembership::factory()->create(['beneficiary_id' => $ben->id, 'household_id' => $hh->id]);

        $body = $this->send('partner', 'GET', '/api/v1/gis/coverage?level=lga')->assertOk()->json('data');
        $dutse = collect($body['rows'])->firstWhere('key', 'dutse');

        // Funding DENSITY = attributed budget of the partner's OWN funded activities only.
        $this->assertSame(2_000_000, $dutse['funding_allocated']);
        $this->assertSame(400_000, $dutse['benefit_value']);   // funds delivered (value)
        $this->assertSame(1, $dutse['served']);                // coverage (absolute)
        $this->assertSame(1, $dutse['beneficiary_count']);     // the funded cohort in Dutse
        $this->assertSame(1, $dutse['households']);            // cohort household
        $this->assertSame(1, $dutse['active_programmes']);
        $this->assertSame(['MDA A'], $dutse['mdas']);          // implementing MDA

        // The co-funder's activity (Hadejia, another funder) never leaks into the partner's map.
        $hadejia = collect($body['rows'])->firstWhere('key', 'hadejia');
        $this->assertTrue($hadejia === null || $hadejia['funding_allocated'] === 0);
    }
}
