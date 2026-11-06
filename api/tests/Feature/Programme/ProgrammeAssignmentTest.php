<?php

declare(strict_types=1);

namespace Tests\Feature\Programme;

use App\Domain\Access\Enums\MdaType;
use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Assigning catalog programmes to MDAs (§10, revised 2026-11-05).
 *
 * The catalog is shared and large; no MDA delivers all of it. An administrator records
 * which programmes each organisation offers, and that assignment is what every MDA-facing
 * picker shows and what every entry point that creates work enforces.
 *
 * What must NOT change, and is asserted here too: an MDA can still create a programme of
 * its own and it still waits for the System Administrator. Assignment is a second,
 * separate gate on the SHARED catalog, not a replacement for that approval.
 */
class ProgrammeAssignmentTest extends TestCase
{
    use RefreshDatabase;

    private Mda $health;

    private Mda $agric;

    private Mda $partner;

    private Programme $healthProgramme;

    private Programme $agricProgramme;

    private Programme $unassigned;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->health = Mda::factory()->create(['name' => 'Ministry of Health', 'type' => MdaType::Ministry]);
        $this->agric = Mda::factory()->create(['name' => 'Ministry of Agric', 'type' => MdaType::Ministry]);
        $this->partner = Mda::factory()->create(['name' => 'Save the Children', 'type' => MdaType::Partner]);

        $this->users['admin'] = $this->user(null, RoleKey::SystemAdministrator);
        $this->users['coordination'] = $this->user(null, RoleKey::SpCoordination);
        $this->users['health'] = $this->user($this->health, RoleKey::MdaAdmin);
        $this->users['agric'] = $this->user($this->agric, RoleKey::MdaAdmin);
        $this->users['partner'] = $this->user($this->partner, RoleKey::MdaAdmin);

        $this->healthProgramme = Programme::factory()->individual()->create(['name' => 'Health Insurance', 'status' => 'active']);
        $this->agricProgramme = Programme::factory()->individual()->create(['name' => 'Goat Revolving Scheme', 'status' => 'active']);
        $this->unassigned = Programme::factory()->individual()->create(['name' => 'Nobody Runs This', 'status' => 'active']);

        $this->assign($this->health, $this->healthProgramme);
        $this->assign($this->agric, $this->agricProgramme);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    /** Seed an assignment the way the inventory seeder does — not through the API. */
    private function assign(Mda $mda, Programme $programme, string $source = 'inventory'): void
    {
        DB::table('mda_programme')->insert([
            'id' => (string) Str::uuid(),
            'mda_id' => $mda->id,
            'programme_id' => $programme->id,
            'source' => $source,
            'assigned_by' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /** @param array<string, mixed> $body */
    private function as(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        // One role per test method: the auth guard caches the resolved user.
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /** @return list<string> */
    private function catalogNames(string $key): array
    {
        return array_column($this->as($key, 'GET', '/api/v1/programmes?per_page=100')->assertOk()->json('data'), 'name');
    }

    /* ------------------------------------------------- what an MDA now sees */

    public function test_an_mda_sees_only_the_programmes_it_was_assigned(): void
    {
        // The complaint this feature answers: every MDA used to see the whole catalog
        // in every picker, so an officer at Health scrolled past agriculture.
        $this->assertSame(['Health Insurance'], $this->catalogNames('health'));
        $this->assertSame(['Goat Revolving Scheme'], $this->catalogNames('agric'));
    }

    public function test_an_mda_with_no_assignments_sees_an_empty_catalog(): void
    {
        // Confirmed with the owner (2026-11-05): an organisation nobody has configured
        // yet offers nothing, and must be assigned something before it can work. An
        // empty list is the honest answer, not a bug — the console says so in words.
        $bare = Mda::factory()->create(['name' => 'Ministry of Nothing Yet', 'type' => MdaType::Ministry]);
        $this->users['bare'] = $this->user($bare, RoleKey::MdaAdmin);

        $this->assertSame([], $this->catalogNames('bare'));
    }

    public function test_oversight_roles_still_see_the_whole_catalog(): void
    {
        foreach (['admin', 'coordination'] as $key) {
            $names = $this->catalogNames($key);

            $this->assertContains('Health Insurance', $names);
            $this->assertContains('Goat Revolving Scheme', $names);
            $this->assertContains('Nobody Runs This', $names, 'an administrator must see what nobody has been assigned');
        }
    }

    public function test_a_partner_organisation_is_not_narrowed(): void
    {
        // A Development Partner funds across MDAs and holds no assignments of its own.
        // Narrowing it would empty every list it reads, which is a different bug from
        // the one being fixed.
        $names = $this->catalogNames('partner');

        $this->assertContains('Health Insurance', $names);
        $this->assertContains('Goat Revolving Scheme', $names);
    }

    public function test_an_mda_still_sees_its_own_programme_while_it_waits_for_approval(): void
    {
        // Its own submission has no assignment row yet — approval writes that — so
        // without the owner clause the author could not see what they just submitted.
        $this->as('health', 'POST', '/api/v1/programmes', [
            'name' => 'Our Own Idea',
            'type' => 'individual',
            'status' => 'draft',
        ])->assertCreated();

        $names = $this->catalogNames('health');

        $this->assertContains('Our Own Idea', $names);
        $this->assertContains('Health Insurance', $names);
        $this->assertNotContains('Goat Revolving Scheme', $names);
    }

    /* ------------------------------------------------------------ enforcement */

    public function test_an_mda_cannot_build_an_activity_under_an_unassigned_programme(): void
    {
        // Hiding is not enforcing: the API is reachable without the picker, which is
        // the argument IsRunnableProgramme already makes for the archive check.
        $response = $this->as('health', 'POST', '/api/v1/activities', [
            'programme_id' => $this->agricProgramme->id,
            'name' => 'Sneaking in',
            'involves_beneficiaries' => false,
        ])->assertStatus(422);

        $this->assertStringContainsString('not assigned to your MDA', (string) json_encode($response->json()));
        $this->assertDatabaseMissing('activities', ['name' => 'Sneaking in']);
    }

    public function test_an_mda_can_build_an_activity_under_a_programme_it_was_assigned(): void
    {
        $this->as('health', 'POST', '/api/v1/activities', [
            'programme_id' => $this->healthProgramme->id,
            'name' => 'Enrolment drive',
            'involves_beneficiaries' => false,
        ])->assertCreated();

        $this->assertDatabaseHas('activities', [
            'name' => 'Enrolment drive',
            'programme_id' => $this->healthProgramme->id,
            'owner_mda_id' => $this->health->id,
        ]);
    }

    public function test_the_refusal_names_the_programme_so_it_can_be_acted_on(): void
    {
        // An unassigned CENTRAL entry is still readable, so naming it confirms nothing
        // the caller could not look up — and a refusal that does not say WHICH
        // programme leaves the officer nothing to take to an administrator.
        $response = $this->as('health', 'POST', '/api/v1/activities', [
            'programme_id' => $this->unassigned->id,
            'name' => 'Blocked',
            'involves_beneficiaries' => false,
        ])->assertStatus(422);

        $this->assertStringContainsString('Nobody Runs This', (string) json_encode($response->json()));
    }

    public function test_an_mda_cannot_enrol_anyone_into_an_unassigned_programme(): void
    {
        // §10 lists enrolment with activity, import and routing as work that needs a
        // usable programme. Enrolment resolves the programme from the PATH, so it
        // cannot use IsRunnableProgramme and had its own copy of the checks — which
        // would have left this one path open.
        $person = Beneficiary::factory()->create(['owner_mda_id' => $this->health->id]);

        $this->as('health', 'POST', "/api/v1/programmes/{$this->agricProgramme->id}/enrollments", [
            'beneficiary_id' => $person->id,
        ])->assertStatus(422)->assertJsonPath('error.code', 'PROGRAMME_NOT_ASSIGNED');

        $this->assertDatabaseMissing('enrollments', ['programme_id' => $this->agricProgramme->id]);

        // The bulk path is the same endpoint's sibling and must not be the way round it.
        $this->as('health', 'POST', "/api/v1/programmes/{$this->agricProgramme->id}/enrollments/bulk", [
            'beneficiary_ids' => [$person->id],
        ])->assertStatus(422)->assertJsonPath('error.code', 'PROGRAMME_NOT_ASSIGNED');

        // …and the assigned one still works, so the gate is not simply off.
        $this->as('health', 'POST', "/api/v1/programmes/{$this->healthProgramme->id}/enrollments", [
            'beneficiary_id' => $person->id,
        ])->assertCreated();
    }

    public function test_a_partner_organisation_is_not_blocked_by_assignment(): void
    {
        // The exemption proved through the RULE, not just the list: a partner holds no
        // assignments of its own, so if the rule applied to it, it could never record
        // anything. (A System Administrator cannot be used for this: ActivityPolicy has
        // always required an mda_id to create an activity, which is a separate rule and
        // not one this feature touches.)
        $this->as('partner', 'POST', '/api/v1/activities', [
            'programme_id' => $this->unassigned->id,
            'name' => 'Partner activity',
            'involves_beneficiaries' => false,
        ])->assertCreated();

        $this->assertDatabaseHas('activities', [
            'name' => 'Partner activity',
            'owner_mda_id' => $this->partner->id,
        ]);
    }

    /* --------------------------------------------------- assigning (the admin) */

    public function test_an_administrator_assigns_programmes_to_an_mda(): void
    {
        $this->as('admin', 'PUT', "/api/v1/mdas/{$this->health->id}/programmes", [
            'programme_ids' => [$this->healthProgramme->id, $this->unassigned->id],
        ])->assertOk()
            ->assertJsonPath('data.assigned', [$this->unassigned->id])
            ->assertJsonPath('data.unassigned', [])
            ->assertJsonPath('data.unchanged', 1);

        $this->assertDatabaseHas('mda_programme', [
            'mda_id' => $this->health->id,
            'programme_id' => $this->unassigned->id,
            'source' => 'assignment',
            'assigned_by' => $this->users['admin']->id,
        ]);

        $this->assertSame(['Health Insurance', 'Nobody Runs This'], $this->sorted($this->catalogNames('health')));
    }

    public function test_sp_coordination_can_also_assign(): void
    {
        // Stakeholder decision (2026-11-05): assigning is catalog administration, which
        // SP Coordination already co-owns. Deciding another MDA's SUBMISSION is not, and
        // stays the System Administrator's alone — asserted below.
        $this->as('coordination', 'PUT', "/api/v1/mdas/{$this->health->id}/programmes", [
            'programme_ids' => [$this->healthProgramme->id, $this->unassigned->id],
        ])->assertOk();

        $this->assertDatabaseHas('mda_programme', [
            'mda_id' => $this->health->id,
            'programme_id' => $this->unassigned->id,
        ]);
    }

    public function test_an_mda_can_never_assign_itself_a_programme(): void
    {
        // Administrator-push only (decision 2026-11-05). If an MDA could assign itself,
        // the gate would be decoration.
        foreach (['health', 'agric', 'partner'] as $key) {
            $this->as($key, 'PUT', "/api/v1/mdas/{$this->health->id}/programmes", [
                'programme_ids' => [$this->healthProgramme->id, $this->unassigned->id],
            ])->assertStatus(403);

            $this->as($key, 'GET', "/api/v1/mdas/{$this->health->id}/programmes")->assertStatus(403);

            $this->as($key, 'DELETE', "/api/v1/mdas/{$this->health->id}/programmes/{$this->healthProgramme->id}")
                ->assertStatus(403);
        }

        $this->assertDatabaseMissing('mda_programme', [
            'mda_id' => $this->health->id,
            'programme_id' => $this->unassigned->id,
        ]);
    }

    public function test_unassigning_removes_the_programme_from_that_mdas_catalog(): void
    {
        $this->as('admin', 'PUT', "/api/v1/mdas/{$this->health->id}/programmes", ['programme_ids' => []])
            ->assertOk()
            ->assertJsonPath('data.unassigned', [$this->healthProgramme->id]);

        $this->assertSame([], $this->catalogNames('health'));

        // The programme itself is untouched — unassigning is not archiving.
        $this->assertDatabaseHas('programmes', ['id' => $this->healthProgramme->id, 'deleted_at' => null]);
        $this->assertNull($this->healthProgramme->fresh()->archived_at);
    }

    public function test_one_programme_can_be_removed_on_its_own(): void
    {
        $this->assign($this->health, $this->unassigned, 'assignment');

        $this->as('admin', 'DELETE', "/api/v1/mdas/{$this->health->id}/programmes/{$this->unassigned->id}")
            ->assertOk()->assertJsonPath('data.removed', true);

        $this->assertSame(['Health Insurance'], $this->catalogNames('health'));
    }

    public function test_removing_something_never_assigned_is_reported_not_raised(): void
    {
        // The end state the caller asked for is the end state they have.
        $this->as('admin', 'DELETE', "/api/v1/mdas/{$this->health->id}/programmes/{$this->unassigned->id}")
            ->assertOk()->assertJsonPath('data.removed', false);
    }

    public function test_assigning_one_mda_does_not_touch_another(): void
    {
        $this->as('admin', 'PUT', "/api/v1/mdas/{$this->health->id}/programmes", ['programme_ids' => []])->assertOk();

        // Agric is untouched: the sync is scoped to the MDA in the path, and a delete
        // keyed on programme_id alone would have wiped every MDA's row for it.
        $this->assertSame(['Goat Revolving Scheme'], $this->catalogNames('agric'));
    }

    public function test_the_same_programme_can_be_assigned_to_several_mdas(): void
    {
        // §10 keeps "one programme, many MDAs, separate activities" — assignment must
        // not quietly turn the catalog into one-owner-per-programme.
        $this->as('admin', 'PUT', "/api/v1/mdas/{$this->agric->id}/programmes", [
            'programme_ids' => [$this->agricProgramme->id, $this->healthProgramme->id],
        ])->assertOk();

        $this->assertContains('Health Insurance', $this->catalogNames('agric'));
        $this->assertContains('Health Insurance', $this->catalogNames('health'));
    }

    public function test_re_saving_an_unchanged_list_rewrites_nothing(): void
    {
        $before = DB::table('mda_programme')->where('mda_id', $this->health->id)->first();

        $this->as('admin', 'PUT', "/api/v1/mdas/{$this->health->id}/programmes", [
            'programme_ids' => [$this->healthProgramme->id],
        ])->assertOk()->assertJsonPath('data.unchanged', 1);

        $after = DB::table('mda_programme')->where('mda_id', $this->health->id)->first();

        // Same row, and still recorded as having come from the inventory: re-saving the
        // page must not restamp provenance as an administrator's decision.
        $this->assertSame($before->id, $after->id);
        $this->assertSame('inventory', $after->source);
    }

    /* -------------------------------------------------- the in-use refusal (409) */

    public function test_unassigning_is_refused_while_the_mda_still_runs_the_programme(): void
    {
        $activity = Activity::factory()->forProgramme($this->healthProgramme, $this->health)
            ->create(['name' => 'Live work', 'status' => 'active']);

        $response = $this->as('admin', 'PUT', "/api/v1/mdas/{$this->health->id}/programmes", ['programme_ids' => []])
            ->assertStatus(409)
            ->assertJsonPath('error.code', 'PROGRAMME_ASSIGNMENT_IN_USE');

        // The blocking activity is NAMED: an administrator cannot act on "some
        // activities exist", and these usually belong to someone else.
        $this->assertStringContainsString('Live work', (string) json_encode($response->json()));

        // Nothing was removed — the refusal is not partial.
        $this->assertDatabaseHas('mda_programme', [
            'mda_id' => $this->health->id,
            'programme_id' => $this->healthProgramme->id,
        ]);
        $this->assertSame(['Health Insurance'], $this->catalogNames('health'));
        $this->assertDatabaseHas('activities', ['id' => $activity->id]);
    }

    public function test_the_single_remove_path_refuses_on_the_same_terms(): void
    {
        // A narrower call must not be a way around the guard.
        Activity::factory()->forProgramme($this->healthProgramme, $this->health)->create(['status' => 'draft']);

        $this->as('admin', 'DELETE', "/api/v1/mdas/{$this->health->id}/programmes/{$this->healthProgramme->id}")
            ->assertStatus(409)->assertJsonPath('error.code', 'PROGRAMME_ASSIGNMENT_IN_USE');
    }

    public function test_finished_work_does_not_block_unassigning(): void
    {
        // History stays readable through the programme's own detail view whether or not
        // the assignment stands. An administrator ending an organisation's involvement
        // in a completed programme should not have to archive the past first.
        Activity::factory()->forProgramme($this->healthProgramme, $this->health)->create(['status' => 'completed']);

        $this->as('admin', 'PUT', "/api/v1/mdas/{$this->health->id}/programmes", ['programme_ids' => []])->assertOk();

        $this->assertSame([], $this->catalogNames('health'));
    }

    public function test_another_mdas_activity_does_not_block_this_one(): void
    {
        // The guard is per (MDA, programme). Agric running a programme is no reason to
        // keep Health assigned to it.
        $this->assign($this->health, $this->agricProgramme, 'assignment');
        Activity::factory()->forProgramme($this->agricProgramme, $this->agric)->create(['status' => 'active']);

        $this->as('admin', 'DELETE', "/api/v1/mdas/{$this->health->id}/programmes/{$this->agricProgramme->id}")
            ->assertOk()->assertJsonPath('data.removed', true);
    }

    /* ----------------------------------------------- the admin's own listing */

    public function test_the_assignment_listing_shows_provenance_and_this_mdas_usage(): void
    {
        Activity::factory()->forProgramme($this->healthProgramme, $this->health)->create();
        Activity::factory()->forProgramme($this->healthProgramme, $this->agric)->create();

        $rows = $this->as('admin', 'GET', "/api/v1/mdas/{$this->health->id}/programmes")
            ->assertOk()->assertJsonPath('data.mda.name', 'Ministry of Health')
            ->json('data.programmes');

        $this->assertCount(1, $rows);
        $this->assertSame('Health Insurance', $rows[0]['name']);
        $this->assertSame('inventory', $rows[0]['assignment']['source']);
        // THIS MDA's activities, not the world's — an administrator unticking a row
        // needs to know what that organisation has under it.
        $this->assertSame(1, $rows[0]['activities_count']);
    }

    public function test_assignment_provenance_is_absent_where_it_was_not_asked_for(): void
    {
        // The ordinary catalog list does not load the pivot, so nothing downstream has
        // to tell "not assigned" apart from "assignment not requested".
        $row = $this->as('admin', 'GET', '/api/v1/programmes?per_page=100')->assertOk()->json('data.0');

        $this->assertArrayNotHasKey('assignment', $row);
    }

    /* --------------------------------- the approval rule is deliberately unchanged */

    public function test_an_mda_still_creates_its_own_programme_and_still_needs_approval(): void
    {
        // The rule the owner asked to leave alone, asserted end to end so this feature
        // cannot quietly become the way an MDA gets a programme.
        $created = $this->as('health', 'POST', '/api/v1/programmes', [
            'name' => 'Our Own Scheme',
            'type' => 'individual',
            'status' => 'active',
        ])->assertCreated()->json('data');

        $this->assertSame('pending', $created['approval_status']);
        $this->assertSame($this->health->id, $created['owner_mda_id']);

        // Carries no work while it waits — the pre-existing approval gate, not the new one.
        $response = $this->as('health', 'POST', '/api/v1/activities', [
            'programme_id' => $created['id'],
            'name' => 'Too early',
            'involves_beneficiaries' => false,
        ])->assertStatus(422);
        $this->assertStringContainsString('waiting for approval', (string) json_encode($response->json()));

        // Only the System Administrator decides it — not SP Coordination, who CAN assign.
        $this->as('coordination', 'POST', "/api/v1/programmes/{$created['id']}/approve")->assertStatus(403);
        $this->as('admin', 'POST', "/api/v1/programmes/{$created['id']}/approve")->assertOk();

        // Approval records the assignment, so it is immediately usable.
        $this->assertDatabaseHas('mda_programme', [
            'mda_id' => $this->health->id,
            'programme_id' => $created['id'],
            'source' => 'mda',
        ]);

        $this->as('health', 'POST', '/api/v1/activities', [
            'programme_id' => $created['id'],
            'name' => 'Now allowed',
            'involves_beneficiaries' => false,
        ])->assertCreated();
    }

    public function test_an_approved_own_programme_is_not_visible_to_another_mda(): void
    {
        // Assignment did not loosen MDA isolation: approval clears a programme for its
        // owner, it does not promote it into the shared catalog (§10).
        $mine = Programme::factory()->individual()->create([
            'owner_mda_id' => $this->health->id,
            'approval_status' => 'approved',
            'name' => 'Private To Health',
            'status' => 'active',
        ]);
        $this->assign($this->health, $mine, 'mda');

        $this->assertContains('Private To Health', $this->catalogNames('health'));
        $this->assertNotContains('Private To Health', $this->catalogNames('agric'));
    }

    /* ------------------------------------------------------------- validation */

    public function test_the_whole_list_is_required_and_ids_must_exist(): void
    {
        $this->as('admin', 'PUT', "/api/v1/mdas/{$this->health->id}/programmes", [])->assertStatus(422);

        $this->as('admin', 'PUT', "/api/v1/mdas/{$this->health->id}/programmes", [
            'programme_ids' => [(string) Str::uuid()],
        ])->assertStatus(422);

        // Unchanged by either refusal.
        $this->assertSame(['Health Insurance'], $this->catalogNames('health'));
    }

    public function test_a_duplicate_id_in_the_list_is_rejected_not_double_inserted(): void
    {
        $this->as('admin', 'PUT', "/api/v1/mdas/{$this->health->id}/programmes", [
            'programme_ids' => [$this->unassigned->id, $this->unassigned->id],
        ])->assertStatus(422);

        $this->assertSame(0, DB::table('mda_programme')
            ->where('mda_id', $this->health->id)
            ->where('programme_id', $this->unassigned->id)
            ->count());
    }

    public function test_assigning_is_audited_with_what_changed(): void
    {
        $this->as('admin', 'PUT', "/api/v1/mdas/{$this->health->id}/programmes", [
            'programme_ids' => [$this->unassigned->id],
        ])->assertOk();

        $entry = DB::table('audit_log')->where('action', 'mda.programmes_assigned')->latest('created_at')->first();

        $this->assertNotNull($entry);
        $this->assertSame($this->users['admin']->id, $entry->actor_id);
        $this->assertSame($this->health->id, $entry->entity_id);

        $after = json_decode((string) $entry->after, true);
        $this->assertSame([$this->unassigned->id], $after['assigned']);
        $this->assertSame([$this->healthProgramme->id], $after['unassigned']);
    }

    /** @param list<string> $names @return list<string> */
    private function sorted(array $names): array
    {
        sort($names);

        return $names;
    }
}
