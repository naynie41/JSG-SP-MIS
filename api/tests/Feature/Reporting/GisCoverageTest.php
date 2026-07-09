<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Models\Beneficiary;
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

        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['exec'] = $this->user(null, RoleKey::Executive);
        $this->users['noRole'] = User::factory()->create(['mda_id' => $this->mdaA->id, 'role_id' => null]);

        Beneficiary::factory()->count(2)->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'lga' => 'hadejia']);
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

    public function test_coverage_requires_dashboard_permission(): void
    {
        $this->send('noRole', 'GET', '/api/v1/gis/coverage')->assertStatus(403);
    }
}
