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
use Tests\Concerns\AssignsProgrammes;
use Tests\TestCase;

/**
 * "Participation" for the MDA console's Programmes module: the catalog programmes this
 * MDA runs activities under. The catalog itself stays global and read-only for an MDA
 * (CLAUDE.md §10) — participation is a VIEW over it, never ownership.
 *
 * Participation and ASSIGNMENT (§10, revised 2026-11-05) are two different questions,
 * and both still exist. Assignment is what an administrator declared the MDA offers;
 * participation is what it has actually built activities under. A freshly assigned
 * programme is assigned but not yet participated in, which is why the console lists
 * assignments and shows the activity count beside each.
 */
class MdaProgrammeParticipationTest extends TestCase
{
    use AssignsProgrammes, RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Programme $runByA;

    private Programme $runByB;

    private Programme $runByNobody;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaAdmin);
        $this->users['adminA'] = $this->user($this->mdaA, RoleKey::MdaAdmin);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaAdmin);
        $this->users['sysadmin'] = $this->user(null, RoleKey::SystemAdministrator);

        $this->runByA = Programme::factory()->individual()->create(['name' => 'Cash Transfer', 'status' => 'active']);
        $this->runByB = Programme::factory()->individual()->create(['name' => 'Skills Training', 'status' => 'active']);
        $this->runByNobody = Programme::factory()->individual()->create(['name' => 'Unused Programme', 'status' => 'active']);

        // Each MDA is assigned the programme it runs, plus the spare one it has not
        // built anything under yet — the assigned-but-not-participating case that
        // keeps the two ideas apart below (§10, revised 2026-11-05).
        $this->assignProgrammes($this->mdaA, [$this->runByA, $this->runByNobody]);
        $this->assignProgrammes($this->mdaB, [$this->runByB, $this->runByNobody]);

        Activity::factory()->forProgramme($this->runByA, $this->mdaA)->create(['name' => 'A-1']);
        Activity::factory()->forProgramme($this->runByA, $this->mdaA)->create(['name' => 'A-2']);
        Activity::factory()->forProgramme($this->runByB, $this->mdaB)->create(['name' => 'B-1']);
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

    /* ---------------------------------------------------------- participation */

    public function test_participation_lists_only_programmes_this_mda_runs(): void
    {
        $names = array_column(
            $this->send('officerA', 'GET', '/api/v1/programmes?filter[participating]=1')->assertOk()->json('data'),
            'name',
        );

        $this->assertSame(['Cash Transfer'], $names);

        // B participates in a different programme entirely.
        $namesB = array_column(
            $this->send('officerB', 'GET', '/api/v1/programmes?filter[participating]=1')->assertOk()->json('data'),
            'name',
        );
        $this->assertSame(['Skills Training'], $namesB);
    }

    public function test_the_unfiltered_catalog_is_what_this_mda_was_assigned(): void
    {
        // REVERSED on 2026-11-05. This test used to assert that an MDA browsing the
        // unfiltered catalog saw everything in it, on the reasoning that it had to in
        // order to pick a programme for an activity. The owner overturned that: an MDA
        // picks from what it OFFERS, and 115 state-wide entries in a dropdown is the
        // problem, not the feature.
        //
        // Participation is still a view rather than a restriction — within the
        // assignment. The distinction the old test was protecting survives as the
        // first assertion: a programme assigned but not yet participated in is listed.
        $names = array_column(
            $this->send('officerA', 'GET', '/api/v1/programmes')->assertOk()->json('data'),
            'name',
        );

        $this->assertContains('Unused Programme', $names, 'assigned but not yet run is still part of this MDA catalog');
        $this->assertContains('Cash Transfer', $names);
        $this->assertNotContains('Skills Training', $names, "another MDA's assignment must not appear");
    }

    public function test_activity_counts_are_scoped_to_the_callers_mda(): void
    {
        // The count beside a programme must be THIS MDA's activities, not the world's.
        $rows = collect($this->send('officerA', 'GET', '/api/v1/programmes')->assertOk()->json('data'))->keyBy('name');

        $this->assertSame(2, $rows['Cash Transfer']['activities_count']);
        $this->assertSame(0, $rows['Unused Programme']['activities_count']);
        // Skills Training used to appear here with a count of 0, which proved another
        // MDA's activity was not counted. It is no longer in A's catalog at all — a
        // stronger form of the same guarantee, and now asserted as absence.
        $this->assertArrayNotHasKey('Skills Training', $rows->all());
    }

    public function test_an_oversight_role_sees_participation_across_all_mdas(): void
    {
        // A System Administrator is not MDA-bound, so "in use anywhere" is the
        // meaningful reading of the same filter.
        $names = array_column(
            $this->send('sysadmin', 'GET', '/api/v1/programmes?filter[participating]=1')->assertOk()->json('data'),
            'name',
        );

        sort($names);
        $this->assertSame(['Cash Transfer', 'Skills Training'], $names);
        $this->assertNotContains('Unused Programme', $names);
    }

    /* --------------------------------------------------- activities in context */

    public function test_activities_in_a_programme_are_scoped_to_the_callers_mda(): void
    {
        $rows = $this->send('officerA', 'GET', "/api/v1/activities?filter[programme_id]={$this->runByA->id}")
            ->assertOk()->json('data');

        $this->assertCount(2, $rows);
        foreach ($rows as $row) {
            $this->assertSame($this->mdaA->id, $row['owner_mda_id']);
        }

        // A's view of a programme it does not run is empty, not another MDA's work.
        $this->send('officerA', 'GET', "/api/v1/activities?filter[programme_id]={$this->runByB->id}")
            ->assertOk()->assertJsonCount(0, 'data');
    }

    /* ------------------------------------------------- catalog stays read-only */

    public function test_an_mda_can_never_edit_a_catalog_programme(): void
    {
        // An MDA creating a programme of its own (§10, revised) never reaches the
        // CENTRAL catalog: the shared entry it runs stays untouchable.
        foreach (['officerA', 'adminA'] as $who) {
            $this->send($who, 'PATCH', "/api/v1/programmes/{$this->runByA->id}", ['name' => 'Renamed'])
                ->assertStatus(403);

            $this->send($who, 'POST', "/api/v1/programmes/{$this->runByA->id}/archive")->assertStatus(403);
        }

        $this->assertDatabaseHas('programmes', ['id' => $this->runByA->id, 'name' => 'Cash Transfer']);
    }

    public function test_an_mda_creates_activities_under_a_catalog_programme(): void
    {
        // The MDA-owned half of §10: it cannot touch the catalog, but it owns its
        // activities beneath it.
        $this->send('officerA', 'POST', '/api/v1/activities', [
            'programme_id' => $this->runByNobody->id,
            'name' => 'New activity',
            'involves_beneficiaries' => false,
        ])->assertCreated();

        $this->assertDatabaseHas('activities', [
            'name' => 'New activity',
            'programme_id' => $this->runByNobody->id,
            'owner_mda_id' => $this->mdaA->id,
        ]);

        // …and that programme is now one it participates in.
        $names = array_column(
            $this->send('officerA', 'GET', '/api/v1/programmes?filter[participating]=1')->assertOk()->json('data'),
            'name',
        );
        $this->assertContains('Unused Programme', $names);
    }
}
