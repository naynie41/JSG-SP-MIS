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
 * Activity management (PRD §10, ARCH §12.4, FR-PRG-02): any MDA may create an
 * activity that runs a GLOBAL catalog programme; it is owned by the creating MDA
 * and scoped to it (owner-only mutation). Covers CRUD, RBAC, validation and audit.
 */
class ActivityManagementTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Programme $programmeA;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);
        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);
        $this->users['viewer'] = $this->user($this->mdaA, RoleKey::MneOfficer); // activity.view only
        $this->users['oversight'] = $this->user($this->mdaB, RoleKey::Executive);

        $this->programmeA = Programme::factory()->create(); // a global catalog programme
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'programme_id' => $this->programmeA->id,
            'involves_beneficiaries' => false, // no-beneficiary activity (POST /activities path)
            'name' => 'Q1 Cash Disbursement',
            'description' => 'First quarter payout',
            'lga' => 'dutse',
            'ward' => 'Ward 3',
            'location_description' => 'Dutse central',
            'schedule' => ['cadence' => 'monthly'],
            'starts_on' => '2026-01-01',
            'ends_on' => '2026-03-31',
            'budget_amount' => 10_000_000,
            'funding_source' => 'State budget',
            'status' => 'active',
        ], $overrides);
    }

    public function test_activity_without_beneficiaries_saves_alone(): void
    {
        $this->send('officerA', 'POST', '/api/v1/activities', $this->payload())
            ->assertCreated()
            ->assertJsonPath('data.involves_beneficiaries', false)
            ->assertJsonPath('data.target_beneficiaries', null);
    }

    public function test_no_beneficiary_activity_rejects_a_target_or_file(): void
    {
        // A target on the no-beneficiary path is prohibited.
        $this->send('officerA', 'POST', '/api/v1/activities', $this->payload(['target_beneficiaries' => 500]))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'target_beneficiaries']);
    }

    public function test_beneficiary_involving_activity_cannot_be_created_without_the_upload(): void
    {
        // involves_beneficiaries=true requires the mandatory upload wizard, not this endpoint.
        $this->send('officerA', 'POST', '/api/v1/activities', $this->payload(['involves_beneficiaries' => true]))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'involves_beneficiaries']);
    }

    public function test_mda_creates_an_activity_owned_by_its_own_mda(): void
    {
        $this->send('officerA', 'POST', '/api/v1/activities', $this->payload())
            ->assertCreated()
            ->assertJsonPath('data.name', 'Q1 Cash Disbursement')
            ->assertJsonPath('data.programme_id', $this->programmeA->id)
            ->assertJsonPath('data.owner_mda_id', $this->mdaA->id) // the CREATING MDA
            ->assertJsonPath('data.budget_amount', 10_000_000)
            ->assertJsonPath('data.funding_source', 'State budget')
            ->assertJsonPath('data.lga', 'dutse');

        $activity = Activity::query()->firstOrFail();
        $this->assertSame($this->mdaA->id, $activity->owner_mda_id);
        $this->assertDatabaseHas('audit_log', [
            'action' => 'activity.created',
            'entity_id' => $activity->id,
            'actor_id' => $this->users['officerA']->id,
        ]);
    }

    public function test_any_mda_can_run_the_global_catalog_programme(): void
    {
        // A different MDA runs the same catalog programme through its OWN activity.
        $this->send('officerB', 'POST', '/api/v1/activities', $this->payload())
            ->assertCreated()
            ->assertJsonPath('data.owner_mda_id', $this->mdaB->id);

        $this->assertSame(1, Activity::query()->withoutGlobalScopes()->where('owner_mda_id', $this->mdaB->id)->count());
    }

    public function test_create_requires_permission(): void
    {
        $this->send('viewer', 'POST', '/api/v1/activities', $this->payload())->assertStatus(403);
    }

    public function test_activity_validation(): void
    {
        // Missing name.
        $this->send('officerA', 'POST', '/api/v1/activities', $this->payload(['name' => '']))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'name']);

        // Unknown LGA.
        $this->send('officerA', 'POST', '/api/v1/activities', $this->payload(['lga' => 'nowhere']))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'lga']);

        // Non-existent programme.
        $this->send('officerA', 'POST', '/api/v1/activities', $this->payload(['programme_id' => '00000000-0000-0000-0000-000000000000']))
            ->assertStatus(422)
            ->assertJsonFragment(['field' => 'programme_id']);
    }

    public function test_list_is_scoped_and_filterable_by_programme(): void
    {
        Activity::factory()->forProgramme($this->programmeA, $this->mdaA)->count(2)->create();
        $programmeB = Programme::factory()->create();
        Activity::factory()->forProgramme($programmeB, $this->mdaB)->create();

        // Owner A sees only its two activities.
        $this->send('officerA', 'GET', '/api/v1/activities')
            ->assertOk()
            ->assertJsonCount(2, 'data');

        // Oversight sees all three, filterable by programme.
        $this->send('oversight', 'GET', '/api/v1/activities')->assertOk()->assertJsonCount(3, 'data');
        $this->send('oversight', 'GET', "/api/v1/activities?filter[programme_id]={$programmeB->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_owner_can_update_and_archive_but_another_mda_cannot(): void
    {
        $activity = Activity::factory()->forProgramme($this->programmeA, $this->mdaA)->create(['status' => 'draft']);

        $this->send('officerA', 'PATCH', "/api/v1/activities/{$activity->id}", ['status' => 'active', 'target_beneficiaries' => 999])
            ->assertOk()
            ->assertJsonPath('data.status', 'active')
            ->assertJsonPath('data.target_beneficiaries', 999);

        // Another MDA cannot see or mutate it.
        $this->send('officerB', 'GET', "/api/v1/activities/{$activity->id}")->assertStatus(404);
        $this->send('officerB', 'PATCH', "/api/v1/activities/{$activity->id}", ['name' => 'Hijacked'])->assertStatus(403);
        $this->send('officerB', 'POST', "/api/v1/activities/{$activity->id}/archive")->assertStatus(403);

        // Owner archives it.
        $this->send('officerA', 'POST', "/api/v1/activities/{$activity->id}/archive")
            ->assertOk()
            ->assertJsonPath('data.status', 'archived');

        $this->assertDatabaseHas('audit_log', ['action' => 'activity.updated', 'entity_id' => $activity->id]);
    }
}
