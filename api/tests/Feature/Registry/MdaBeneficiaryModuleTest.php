<?php

declare(strict_types=1);

namespace Tests\Feature\Registry;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\MatchingConfigSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The MDA console's Beneficiaries module: browse, import, correct — and, critically,
 * the ABSENCE of a manual-creation path. Records enter only through an activity-bound
 * upload (CLAUDE.md §9, FR-REG-10); corrections are the owner MDA's alone (FR-OWN-02).
 */
class MdaBeneficiaryModuleTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Activity $activityA;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(MatchingConfigSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaAdmin);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaAdmin);

        $programme = Programme::factory()->individual()->create(['status' => 'active']);
        $this->activityA = Activity::factory()->forProgramme($programme, $this->mdaA)
            ->create(['name' => 'A activity', 'involves_beneficiaries' => true]);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /* ------------------------------------------------- no manual creation path */

    public function test_no_create_beneficiary_endpoint_exists_at_all(): void
    {
        // The strongest form of "no manual create": not a hidden button, not a denied
        // policy — no route. §9 makes activity-bound upload the only way in.
        $creates = collect(Route::getRoutes()->getRoutes())
            ->filter(fn ($r): bool => in_array('POST', $r->methods(), true))
            ->map(fn ($r): string => $r->uri())
            ->filter(fn (string $uri): bool => $uri === 'api/v1/beneficiaries' || $uri === 'api/v1/households')
            ->values();

        $this->assertCount(0, $creates, 'a manual create endpoint must not exist: '.$creates->implode(', '));
    }

    public function test_posting_a_beneficiary_directly_is_not_routable(): void
    {
        $this->send('officerA', 'POST', '/api/v1/beneficiaries', [
            'first_name' => 'Manual', 'last_name' => 'Entry',
        ])->assertStatus(405);
    }

    /* ------------------------------------------------------------------ browse */

    public function test_browse_is_scoped_to_the_callers_mda(): void
    {
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'first_name' => 'Aisha']);
        Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'first_name' => 'Binta']);

        $names = array_column($this->send('officerA', 'GET', '/api/v1/beneficiaries')->assertOk()->json('data'), 'first_name');

        $this->assertContains('Aisha', $names);
        $this->assertNotContains('Binta', $names, "another MDA's record must never be listed");
    }

    public function test_identifiers_are_masked_in_the_browse_payload(): void
    {
        Beneficiary::factory()->create([
            'owner_mda_id' => $this->mdaA->id,
            'nin' => '12345678901',
            'bvn' => '22233344455',
        ]);

        $json = (string) json_encode($this->send('officerA', 'GET', '/api/v1/beneficiaries')->assertOk()->json());

        $this->assertStringNotContainsString('12345678901', $json, 'a raw NIN must never reach the client');
        $this->assertStringNotContainsString('22233344455', $json, 'a raw BVN must never reach the client');
    }

    /* -------------------------------------------------------------- correction */

    public function test_the_owner_mda_can_correct_an_existing_record(): void
    {
        $ben = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'last_name' => 'Belo']);

        $this->send('officerA', 'PATCH', "/api/v1/beneficiaries/{$ben->id}", ['last_name' => 'Bello'])
            ->assertOk();

        $this->assertSame('Bello', $ben->fresh()->last_name);
    }

    public function test_a_non_owner_cannot_correct_another_mdas_record(): void
    {
        // FR-OWN-02: the first registrant owns the core profile and is its sole editor.
        $ben = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'last_name' => 'Belo']);

        $this->send('officerB', 'PATCH', "/api/v1/beneficiaries/{$ben->id}", ['last_name' => 'Hijacked'])
            ->assertStatus(403);

        $this->assertSame('Belo', $ben->fresh()->last_name);
    }

    /* --------------------------------------------- Import Center is activity-bound */

    public function test_an_import_must_name_a_programme_when_it_names_no_activity(): void
    {
        // Programme-first (§9, revised): the activity is optional, but a file that names
        // neither has nothing to register these people under.
        $response = $this->withToken($this->users['officerA']->createToken('t')->plainTextToken)
            ->post('/api/v1/beneficiaries/imports', [
                'file' => UploadedFile::fake()->createWithContent('rows.csv', "first_name,last_name\nA,B\n"),
            ], ['Accept' => 'application/json'])
            ->assertStatus(422);
        $this->app['auth']->forgetGuards();

        $fields = array_column($response->json('error.details'), 'field');
        $this->assertContains('programme_id', $fields);
        $this->assertNotContains('activity_id', $fields, 'an activity is no longer required');
    }

    public function test_an_import_cannot_be_bound_to_another_mdas_activity(): void
    {
        $response = $this->withToken($this->users['officerB']->createToken('t')->plainTextToken)
            ->post('/api/v1/beneficiaries/imports', [
                'activity_id' => $this->activityA->id,
                'file' => UploadedFile::fake()->createWithContent('rows.csv', "first_name,last_name\nA,B\n"),
            ], ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $this->app['auth']->forgetGuards();
    }

    public function test_the_import_center_uses_the_same_pipeline_and_binds_the_activity(): void
    {
        $response = $this->withToken($this->users['officerA']->createToken('t')->plainTextToken)
            ->post('/api/v1/beneficiaries/imports', [
                'activity_id' => $this->activityA->id,
                'source' => 'csv',
                'file' => UploadedFile::fake()->createWithContent(
                    'rows.csv',
                    "first_name,last_name,phone\nAisha,Bello,08030000001\n",
                ),
            ], ['Accept' => 'application/json']);

        $response->assertSuccessful();
        $this->app['auth']->forgetGuards();

        // Same batch table, same preview lifecycle as the wizard's inline upload —
        // the only difference is that the activity already existed.
        $this->assertDatabaseHas('import_batches', [
            'activity_id' => $this->activityA->id,
            'owner_mda_id' => $this->mdaA->id,
        ]);
    }
}
