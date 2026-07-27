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
use App\Domain\Programme\Models\ProgrammeFunder;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Services\DashboardService;
use App\Domain\Reporting\Services\DashboardSnapshotService;
use App\Domain\Reporting\Support\DashboardFilter;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase 6E cross-cutting filters + role tiering. Filters (period / programme / area /
 * MDA) recompute the metrics live and can only ever NARROW the caller's scope — a
 * filter for an out-of-scope MDA/programme yields the empty intersection, never a leak.
 */
class ExecutiveFilterTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Programme $progA;

    private Programme $progB;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['exec'] = $this->user(null, RoleKey::Executive);
        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['partner'] = $this->user(null, RoleKey::DevelopmentPartner);

        // Registry: benA1 (MDA A, dutse, 2026), benA2 (MDA A, kano, 2025), benB1 (MDA B, hadejia, 2026).
        $benA1 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse', 'registration_date' => '2026-03-10']);
        $benA2 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'kano', 'registration_date' => '2025-06-15']);
        $benB1 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'lga' => 'hadejia', 'registration_date' => '2026-05-01']);

        $this->progA = Programme::factory()->individual()->create(['status' => 'active']);
        $this->progB = Programme::factory()->individual()->create(['status' => 'active']);
        $actA = Activity::factory()->forProgramme($this->progA, $this->mdaA)->create(['status' => 'active', 'lga' => 'dutse', 'budget_amount' => 1_000_000, 'target_beneficiaries' => 4]);
        $actB = Activity::factory()->forProgramme($this->progB, $this->mdaB)->create(['status' => 'active', 'lga' => 'hadejia', 'budget_amount' => 500_000, 'target_beneficiaries' => 2]);
        ProgrammeFunder::create(['programme_id' => $this->progA->id, 'user_id' => $this->users['partner']->id]);

        // Deliveries: benA1 via progA (dutse, 2026); benA2 via progA (kano, 2025); benB1 via progB (hadejia, 2026).
        $this->benefit($benA1, $this->progA, $this->mdaA, $actA, 'dutse', '2026-03-10', 100_000);
        $this->benefit($benA2, $this->progA, $this->mdaA, $actA, 'kano', '2025-06-15', 50_000);
        $this->benefit($benB1, $this->progB, $this->mdaB, $actB, 'hadejia', '2026-05-01', 200_000);

        app(DashboardSnapshotService::class)->refreshAll();
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda?->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function benefit(Beneficiary $b, Programme $p, Mda $mda, Activity $a, string $lga, string $date, int $kobo): void
    {
        Benefit::factory()->create([
            'beneficiary_id' => $b->id, 'programme_id' => $p->id, 'mda_id' => $mda->id, 'activity_id' => $a->id,
            'lga' => $lga, 'delivery_date' => $date, 'monetary_value' => $kobo, 'status' => 'verified',
        ]);
    }

    /** @return array<string, mixed> the full dashboard response for a user + filter. */
    private function dashboardFor(string $key, ?DashboardFilter $filter = null): array
    {
        return app(DashboardService::class)->forUser($this->users[$key], $filter);
    }

    private function filter(array $parts): DashboardFilter
    {
        return new DashboardFilter(
            year: $parts['year'] ?? null,
            quarter: $parts['quarter'] ?? null,
            month: $parts['month'] ?? null,
            programmeId: $parts['programme_id'] ?? null,
            lga: $parts['lga'] ?? null,
            ward: $parts['ward'] ?? null,
            mdaId: $parts['mda_id'] ?? null,
        );
    }

    /* -------------------------------------------------------- live vs snapshot */

    public function test_unfiltered_is_snapshot_and_filtered_recomputes_live(): void
    {
        $unfiltered = $this->dashboardFor('exec');
        $this->assertFalse($unfiltered['live']);
        $this->assertSame(3, $unfiltered['metrics']['population']['total_individuals']);

        $filtered = $this->dashboardFor('exec', $this->filter(['year' => 2026]));
        $this->assertTrue($filtered['live']);
        $this->assertSame(['year' => 2026, 'quarter' => null, 'month' => null, 'programme_id' => null, 'lga' => null, 'ward' => null, 'mda_id' => null], $filtered['filters']);
    }

    /* ------------------------------------------------------------ filter axes */

    public function test_period_filter_narrows_registry_and_served(): void
    {
        $y2026 = $this->dashboardFor('exec', $this->filter(['year' => 2026]))['metrics'];
        $this->assertSame(2, $y2026['population']['total_individuals']); // benA1 + benB1
        $this->assertSame(2, $y2026['population']['net_unique_served']);

        $y2025 = $this->dashboardFor('exec', $this->filter(['year' => 2025]))['metrics'];
        $this->assertSame(1, $y2025['population']['total_individuals']); // benA2 only
        $this->assertSame(1, $y2025['population']['net_unique_served']);
    }

    public function test_programme_filter_narrows_every_metric(): void
    {
        $a = $this->dashboardFor('exec', $this->filter(['programme_id' => $this->progA->id]))['metrics'];
        $this->assertSame(2, $a['population']['net_unique_served']);          // benA1 + benA2
        $this->assertSame(150_000, $a['benefits']['disbursed']['total_value']); // 100k + 50k
        $this->assertCount(1, $a['programme_performance']);
        $this->assertSame($this->progA->id, $a['programme_performance'][0]['programme_id']);

        $b = $this->dashboardFor('exec', $this->filter(['programme_id' => $this->progB->id]))['metrics'];
        $this->assertSame(1, $b['population']['net_unique_served']);          // benB1
        $this->assertSame(200_000, $b['benefits']['disbursed']['total_value']);
    }

    public function test_area_filter_narrows_registry_and_coverage(): void
    {
        $m = $this->dashboardFor('exec', $this->filter(['lga' => 'dutse']))['metrics'];
        $this->assertSame(1, $m['registry']['beneficiaries']['total']);      // benA1 only
        $this->assertCount(1, $m['coverage']);
        $this->assertSame('dutse', $m['coverage'][0]['lga']);
    }

    /* --------------------------------------------------- tiering + enforcement */

    public function test_scope_tiers_are_labelled(): void
    {
        $this->assertSame('statewide', $this->dashboardFor('exec')['scope']['tier']);
        $this->assertSame('operational', $this->dashboardFor('officerA')['scope']['tier']);
        $this->assertSame('partner', $this->dashboardFor('partner')['scope']['tier']);
    }

    public function test_a_filter_can_never_escape_the_callers_scope(): void
    {
        // MDA A officer only ever sees MDA A (benA1 + benA2), even filtered.
        $this->assertSame(2, $this->dashboardFor('officerA')['metrics']['population']['total_individuals']);
        $this->assertSame(2, $this->dashboardFor('officerA', $this->filter(['mda_id' => $this->mdaA->id]))['metrics']['population']['total_individuals']);

        // Filtering to MDA B (out of scope) yields the empty intersection — never a leak.
        $escaped = $this->dashboardFor('officerA', $this->filter(['mda_id' => $this->mdaB->id]))['metrics'];
        $this->assertSame(0, $escaped['population']['total_individuals']);
        $this->assertSame(0, $escaped['population']['net_unique_served']);
    }

    public function test_filter_options_are_scoped_to_the_caller(): void
    {
        $exec = $this->dashboardFor('exec')['filter_options'];
        $this->assertEqualsCanonicalizing([$this->progA->id, $this->progB->id], array_column($exec['programmes'], 'id'));
        $this->assertEqualsCanonicalizing([$this->mdaA->id, $this->mdaB->id], array_column($exec['mdas'], 'id'));
        $this->assertEqualsCanonicalizing([2025, 2026], $exec['years']);

        // The MDA officer is only offered their own MDA + the programmes they run.
        $officer = $this->dashboardFor('officerA')['filter_options'];
        $this->assertSame([$this->mdaA->id], array_column($officer['mdas'], 'id'));
        $this->assertSame([$this->progA->id], array_column($officer['programmes'], 'id'));
        $this->assertContains('dutse', $officer['lgas']);
        $this->assertNotContains('hadejia', $officer['lgas']); // MDA B's LGA is out of scope
    }

    /* -------------------------------------------------------------- endpoint */

    public function test_endpoint_applies_and_validates_query_filters(): void
    {
        $token = $this->users['exec']->createToken('t')->plainTextToken;

        $this->withToken($token)->getJson('/api/v1/dashboard?programme_id='.$this->progA->id)
            ->assertOk()
            ->assertJsonPath('data.live', true)
            ->assertJsonPath('data.scope.tier', 'statewide')
            ->assertJsonPath('data.metrics.population.net_unique_served', 2);

        // Out-of-range period is rejected by validation.
        $this->withToken($token)->getJson('/api/v1/dashboard?year=1999')->assertStatus(422);
    }
}
