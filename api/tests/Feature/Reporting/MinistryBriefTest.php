<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\MdaType;
use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Services\DashboardService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The ministry brief on the executive overview (FR-RPT-02).
 *
 * The load-bearing property is that REACH IS NOT SUMMED. Every figure here is
 * `count(distinct beneficiary)` asked of the ledger at the level it is published at.
 * Adding up numbers that are each already deduplicated is the one way to get this
 * wrong, and it is wrong in the direction that flatters the state — which is why the
 * first test below exists.
 */
class MinistryBriefTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    private function executive(): User
    {
        return User::factory()->create([
            'mda_id' => null,
            'role_id' => Role::where('key', RoleKey::Executive->value)->firstOrFail()->id,
        ]);
    }

    private function ministry(string $name): Mda
    {
        return Mda::factory()->create(['name' => $name, 'type' => MdaType::Ministry->value]);
    }

    private function programme(string $name, ProgrammeStatus $status = ProgrammeStatus::Active): Programme
    {
        return Programme::factory()->individual()->create(['name' => $name, 'status' => $status->value]);
    }

    private function activity(Programme $p, Mda $mda, int $budget = 1_000_000): Activity
    {
        return Activity::factory()->forProgramme($p, $mda)->create([
            'budget_amount' => $budget,
            'starts_on' => '2026-01-01',
            'ends_on' => '2026-12-31',
        ]);
    }

    private function serve(Beneficiary $b, Programme $p, Mda $mda, Activity $a, int $kobo = 100_000): void
    {
        Benefit::factory()->create([
            'beneficiary_id' => $b->id, 'programme_id' => $p->id, 'mda_id' => $mda->id,
            'activity_id' => $a->id, 'monetary_value' => $kobo, 'status' => 'verified',
        ]);
    }

    /** @return list<array<string, mixed>> */
    private function brief(): array
    {
        return app(DashboardService::class)->forUser($this->executive())['metrics']['ministry_brief'] ?? [];
    }

    /* ----------------------------------------------------- the deduplication rule */

    public function test_a_person_served_by_two_activities_counts_once_for_the_programme(): void
    {
        // THE POINT OF THIS FEATURE'S BACKEND. Activity reach is net-unique PER
        // ACTIVITY, so adding a programme's activities together double-counts anyone
        // served by both. Here one person is served by both activities of one
        // programme: the brief must say 1, not 2.
        $health = $this->ministry('Ministry of Health');
        $programme = $this->programme('Cash Transfer');
        $first = $this->activity($programme, $health, 600_000);
        $second = $this->activity($programme, $health, 400_000);

        $person = Beneficiary::factory()->create(['owner_mda_id' => $health->id]);
        $this->serve($person, $programme, $health, $first);
        $this->serve($person, $programme, $health, $second);

        $brief = $this->brief();
        $this->assertCount(1, $brief);
        $this->assertSame('Ministry of Health', $brief[0]['mda']);
        $this->assertSame(1, $brief[0]['reached'], 'the ministry reached one person, not two');

        $programmes = $brief[0]['programmes'];
        $this->assertCount(1, $programmes);
        $this->assertSame(1, $programmes[0]['reached'], 'and that programme reached one person, not two');

        // Budget DOES sum — money put behind two activities is twice the money.
        $this->assertSame(1_000_000, $programmes[0]['allocated']);
    }

    public function test_one_person_served_by_two_ministries_counts_for_each(): void
    {
        // Not a contradiction of the above: a person reached by Health and by Education
        // is one person to each of them. What must never happen is these rows being
        // added together to describe the state, and nothing does that.
        $health = $this->ministry('Ministry of Health');
        $education = $this->ministry('Ministry of Education');
        $p1 = $this->programme('Health Programme');
        $p2 = $this->programme('Education Programme');

        $person = Beneficiary::factory()->create(['owner_mda_id' => $health->id]);
        $this->serve($person, $p1, $health, $this->activity($p1, $health));
        $this->serve($person, $p2, $education, $this->activity($p2, $education));

        $brief = collect($this->brief())->keyBy('mda');
        $this->assertSame(1, $brief['Ministry of Health']['reached']);
        $this->assertSame(1, $brief['Ministry of Education']['reached']);
    }

    /* --------------------------------------------------------------- what it picks */

    public function test_it_names_the_three_ministries_that_reached_the_most(): void
    {
        foreach (['Health' => 5, 'Education' => 4, 'Women Affairs' => 3, 'Agriculture' => 2] as $name => $people) {
            $mda = $this->ministry("Ministry of {$name}");
            $programme = $this->programme("{$name} Programme");
            $activity = $this->activity($programme, $mda);
            for ($i = 0; $i < $people; $i++) {
                $this->serve(Beneficiary::factory()->create(['owner_mda_id' => $mda->id]), $programme, $mda, $activity);
            }
        }

        $names = array_column($this->brief(), 'mda');

        $this->assertSame([
            'Ministry of Health',
            'Ministry of Education',
            'Ministry of Women Affairs',
        ], $names, 'three, in reach order');
        $this->assertNotContains('Ministry of Agriculture', $names);
    }

    public function test_a_development_partner_is_not_listed_as_a_ministry(): void
    {
        // A partner delivering alongside government is real, but a brief headed
        // "ministries" that lists one reads as if the state ran it.
        $partner = Mda::factory()->create(['name' => 'UNICEF', 'type' => MdaType::Partner->value]);
        $health = $this->ministry('Ministry of Health');

        $pp = $this->programme('Partner Programme');
        $hp = $this->programme('Health Programme');
        $pa = $this->activity($pp, $partner);
        $ha = $this->activity($hp, $health);

        // The partner reaches more people than the ministry.
        foreach (range(1, 4) as $i) {
            $this->serve(Beneficiary::factory()->create(['owner_mda_id' => $partner->id]), $pp, $partner, $pa);
        }
        $this->serve(Beneficiary::factory()->create(['owner_mda_id' => $health->id]), $hp, $health, $ha);

        $names = array_column($this->brief(), 'mda');
        $this->assertNotContains('UNICEF', $names);
        $this->assertContains('Ministry of Health', $names);
    }

    public function test_only_active_programmes_appear(): void
    {
        $health = $this->ministry('Ministry of Health');
        $live = $this->programme('Running');
        $closed = $this->programme('Finished', ProgrammeStatus::Closed);

        foreach ([$live, $closed] as $p) {
            $activity = $this->activity($p, $health);
            $this->serve(Beneficiary::factory()->create(['owner_mda_id' => $health->id]), $p, $health, $activity);
        }

        $names = array_column($this->brief()[0]['programmes'], 'name');
        $this->assertSame(['Running'], $names);
    }

    public function test_at_most_three_programmes_per_ministry_largest_first(): void
    {
        $health = $this->ministry('Ministry of Health');

        foreach (['Biggest' => 4, 'Middle' => 3, 'Small' => 2, 'Smallest' => 1] as $name => $people) {
            $programme = $this->programme($name);
            $activity = $this->activity($programme, $health);
            for ($i = 0; $i < $people; $i++) {
                $this->serve(Beneficiary::factory()->create(['owner_mda_id' => $health->id]), $programme, $health, $activity);
            }
        }

        $programmes = $this->brief()[0]['programmes'];
        $this->assertSame(['Biggest', 'Middle', 'Small'], array_column($programmes, 'name'));
        $this->assertSame([4, 3, 2], array_column($programmes, 'reached'));
    }

    /* ---------------------------------------------------------------------- scope */

    public function test_an_mda_user_gets_no_brief_at_all(): void
    {
        // It compares ministries. Inside one, it would rank an agency against itself.
        $health = $this->ministry('Ministry of Health');
        $programme = $this->programme('Cash Transfer');
        $activity = $this->activity($programme, $health);
        $this->serve(Beneficiary::factory()->create(['owner_mda_id' => $health->id]), $programme, $health, $activity);

        $mdaUser = User::factory()->create([
            'mda_id' => $health->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);

        $metrics = app(DashboardService::class)->forUser($mdaUser)['metrics'];
        $this->assertNull($metrics['ministry_brief']);
    }
}
