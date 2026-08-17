<?php

declare(strict_types=1);

namespace Tests\Feature\Reference;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Reference\Models\Lga;
use App\Domain\Reference\Models\Ward;
use App\Domain\Reference\Services\ReferenceDataCache;
use App\Domain\Registry\Enums\Lga as LgaEnum;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * The lookup tables, the hierarchy, and the two cascading-selector endpoints.
 */
class AdministrativeDivisionsTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $this->user = User::factory()->create([
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
            'mda_id' => Mda::factory()->create()->id,
        ]);
    }

    private function token(): string
    {
        return $this->user->createToken('test')->plainTextToken;
    }

    // ---------------------------------------------------------------- hierarchy

    public function test_a_ward_belongs_to_an_lga_and_an_lga_has_many_wards(): void
    {
        $lga = Lga::factory()->forEnum(LgaEnum::Dutse)->create();
        $wards = Ward::factory()->count(3)->create(['lga_id' => $lga->id]);

        $this->assertCount(3, $lga->refresh()->wards);
        $this->assertSame($lga->id, $wards->first()?->lga->id);
        $this->assertSame('dutse', $wards->first()?->lga->code);
    }

    public function test_deleting_an_lga_removes_its_wards(): void
    {
        $lga = Lga::factory()->create();
        Ward::factory()->count(2)->create(['lga_id' => $lga->id]);

        $lga->delete();

        $this->assertSame(0, Ward::query()->count());
    }

    public function test_a_ward_cannot_reference_a_missing_lga(): void
    {
        $this->expectException(QueryException::class);

        Ward::query()->create([
            'lga_id' => '01930000-0000-7000-8000-000000000000',
            'code' => 'orphan',
            'name' => 'Orphan',
        ]);
    }

    public function test_lga_codes_are_unique_state_wide_but_ward_codes_are_unique_only_within_an_lga(): void
    {
        $dutse = Lga::factory()->forEnum(LgaEnum::Dutse)->create();
        $kiyawa = Lga::factory()->forEnum(LgaEnum::Kiyawa)->create();

        // The SAME ward code in two different LGAs is legitimate — ward names repeat
        // across Jigawa, which is exactly why the key is (lga_id, code).
        Ward::factory()->create(['lga_id' => $dutse->id, 'code' => 'sabon_gari', 'name' => 'Sabon Gari']);
        Ward::factory()->create(['lga_id' => $kiyawa->id, 'code' => 'sabon_gari', 'name' => 'Sabon Gari']);

        $this->assertSame(2, Ward::query()->where('code', 'sabon_gari')->count());

        // ...but not twice inside one LGA.
        $this->expectException(QueryException::class);
        Ward::factory()->create(['lga_id' => $dutse->id, 'code' => 'sabon_gari', 'name' => 'Sabon Gari']);
    }

    public function test_geometry_columns_exist_and_default_to_null(): void
    {
        // FR-GIS-01 extension point: present, nullable, unused until boundaries are supplied.
        $this->assertTrue(Schema::hasColumn('lgas', 'geometry'));
        $this->assertTrue(Schema::hasColumn('wards', 'geometry'));

        $lga = Lga::factory()->create();
        $ward = Ward::factory()->create(['lga_id' => $lga->id]);

        $this->assertNull($lga->geometry);
        $this->assertNull($ward->geometry);
        $this->assertNull($lga->latitude);
    }

    // ---------------------------------------------------------------- endpoints

    public function test_list_lgas_returns_every_lga_with_its_ward_count(): void
    {
        $dutse = Lga::factory()->forEnum(LgaEnum::Dutse)->create();
        Lga::factory()->forEnum(LgaEnum::Kiyawa)->create();
        Ward::factory()->count(4)->create(['lga_id' => $dutse->id]);

        $response = $this->withToken($this->token())
            ->getJson('/api/v1/reference/lgas')
            ->assertOk()
            ->assertJsonPath('meta.count', 2)
            ->assertJsonStructure(['data' => ['lgas' => [['id', 'code', 'name', 'state', 'ward_count']]]]);

        $lgas = collect($response->json('data.lgas'));

        $this->assertSame('Dutse', $lgas->firstWhere('code', 'dutse')['name']);
        $this->assertSame(4, $lgas->firstWhere('code', 'dutse')['ward_count']);
        // ward_count is how a client detects that ward data has not been loaded yet.
        $this->assertSame(0, $lgas->firstWhere('code', 'kiyawa')['ward_count']);
    }

    public function test_wards_are_filtered_to_the_requested_lga(): void
    {
        $dutse = Lga::factory()->forEnum(LgaEnum::Dutse)->create();
        $kiyawa = Lga::factory()->forEnum(LgaEnum::Kiyawa)->create();

        Ward::factory()->count(3)->create(['lga_id' => $dutse->id]);
        Ward::factory()->count(5)->create(['lga_id' => $kiyawa->id]);

        $response = $this->withToken($this->token())
            ->getJson('/api/v1/reference/wards?lga_id='.$dutse->id)
            ->assertOk()
            ->assertJsonPath('meta.count', 3)
            ->assertJsonPath('meta.lga_id', $dutse->id);

        // Every returned ward belongs to the requested LGA — the filter is the whole point.
        foreach ($response->json('data.wards') as $ward) {
            $this->assertSame($dutse->id, $ward['lga_id']);
        }
    }

    public function test_wards_are_returned_in_name_order(): void
    {
        $lga = Lga::factory()->create();
        foreach (['Zango', 'Andaza', 'Madobi'] as $name) {
            Ward::factory()->create(['lga_id' => $lga->id, 'name' => $name, 'code' => strtolower($name)]);
        }

        $names = collect(
            $this->withToken($this->token())->getJson('/api/v1/reference/wards?lga_id='.$lga->id)->json('data.wards')
        )->pluck('name')->all();

        $this->assertSame(['Andaza', 'Madobi', 'Zango'], $names);
    }

    public function test_listing_wards_requires_an_lga_id(): void
    {
        // Not optional: an unfiltered call would return every ward in the state.
        $this->withToken($this->token())
            ->getJson('/api/v1/reference/wards')
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'VALIDATION_ERROR');
    }

    public function test_an_unknown_lga_id_is_rejected(): void
    {
        $this->withToken($this->token())
            ->getJson('/api/v1/reference/wards?lga_id=01930000-0000-7000-8000-000000000000')
            ->assertStatus(422);
    }

    public function test_reference_lookups_require_authentication(): void
    {
        $this->getJson('/api/v1/reference/lgas')->assertStatus(401);
        $this->getJson('/api/v1/reference/wards?lga_id=x')->assertStatus(401);
    }

    public function test_every_role_can_read_reference_data(): void
    {
        Lga::factory()->forEnum(LgaEnum::Dutse)->create();

        // Deliberately not permission-gated: a permission held by all six roles would
        // deny nothing while implying a distinction the system does not draw.
        foreach (RoleKey::cases() as $roleKey) {
            $user = User::factory()->create([
                'role_id' => Role::where('key', $roleKey->value)->firstOrFail()->id,
                'mda_id' => Mda::factory()->create()->id,
            ]);

            $this->app['auth']->forgetGuards();
            $this->withToken($user->createToken('t')->plainTextToken)
                ->getJson('/api/v1/reference/lgas')
                ->assertOk();
        }
    }

    public function test_there_is_no_write_endpoint_for_reference_data(): void
    {
        // Reference data reproduces its source; it is never authored through the API.
        $token = $this->token();

        $this->withToken($token)->postJson('/api/v1/reference/lgas', ['name' => 'New'])->assertStatus(405);
        $this->app['auth']->forgetGuards();
        $this->withToken($token)->postJson('/api/v1/reference/wards', ['name' => 'New'])->assertStatus(405);
    }

    // ---------------------------------------------------------------- caching

    public function test_the_lga_list_is_cached_and_the_cache_is_invalidated_on_flush(): void
    {
        Lga::factory()->forEnum(LgaEnum::Dutse)->create();

        $this->withToken($this->token())->getJson('/api/v1/reference/lgas')->assertJsonPath('meta.count', 1);

        // Write behind the cache's back — a served response proves it came from cache.
        DB::table('lgas')->delete();

        $this->app['auth']->forgetGuards();
        $this->withToken($this->token())->getJson('/api/v1/reference/lgas')->assertJsonPath('meta.count', 1);

        app(ReferenceDataCache::class)->flush();

        $this->app['auth']->forgetGuards();
        $this->withToken($this->token())->getJson('/api/v1/reference/lgas')->assertJsonPath('meta.count', 0);
    }

    public function test_ward_lists_are_cached_per_lga(): void
    {
        $dutse = Lga::factory()->forEnum(LgaEnum::Dutse)->create();
        $kiyawa = Lga::factory()->forEnum(LgaEnum::Kiyawa)->create();
        Ward::factory()->count(2)->create(['lga_id' => $dutse->id]);
        Ward::factory()->count(1)->create(['lga_id' => $kiyawa->id]);

        $cache = app(ReferenceDataCache::class);

        $this->assertCount(2, $cache->wardsFor($dutse->id));
        $this->assertCount(1, $cache->wardsFor($kiyawa->id));

        DB::table('wards')->delete();

        // Both still served, and one LGA's entry is not the other's.
        $this->assertCount(2, $cache->wardsFor($dutse->id));
        $this->assertCount(1, $cache->wardsFor($kiyawa->id));

        $cache->flush();

        $this->assertCount(0, $cache->wardsFor($dutse->id));
    }

    // ---------------------------------------------------------------- boundary

    public function test_beneficiary_and_household_location_fields_are_untouched(): void
    {
        // The free-text → lookup migration is a separate, deferred step. Until then
        // these stay free text with NO foreign key, and nothing here may have moved them.
        foreach (['beneficiaries', 'households'] as $table) {
            $this->assertTrue(Schema::hasColumn($table, 'lga'), "{$table}.lga must still exist");
            $this->assertTrue(Schema::hasColumn($table, 'ward'), "{$table}.ward must still exist");

            $this->assertFalse(Schema::hasColumn($table, 'lga_id'), "{$table}.lga_id must NOT exist yet");
            $this->assertFalse(Schema::hasColumn($table, 'ward_id'), "{$table}.ward_id must NOT exist yet");
        }
    }

    public function test_lga_codes_line_up_with_the_registry_enum(): void
    {
        // The seam that makes the deferred backfill a join instead of a fuzzy re-match:
        // the code stored here is exactly the value the registry validates against.
        $lga = Lga::factory()->forEnum(LgaEnum::BirninKudu)->create();

        $this->assertSame('birnin_kudu', $lga->code);
        $this->assertSame(LgaEnum::BirninKudu->value, $lga->code);
        $this->assertSame(LgaEnum::BirninKudu->label(), $lga->name);
    }
}
