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
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use App\Domain\Registry\Models\ServiceRequest;
use App\Domain\Reporting\Services\DashboardService;
use App\Domain\Reporting\Services\DashboardSnapshotService;
use App\Domain\Sync\Models\SyncConnector;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Phase 6E executive metrics on the aggregation layer (FR-RPT-01/02): population,
 * demographics, programme performance, registry quality, coordination, coverage
 * banding, trends and the deferred slots. Every figure is scoped + served from the
 * summary snapshot (not a raw scan). The headline is NET-UNIQUE (distinct persons),
 * kept clearly distinct from any GROSS delivery count.
 */
class ExecutiveMetricsTest extends TestCase
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

        // Small, deterministic coverage bands so the banding is testable.
        config(['reporting.coverage_bands.green_min' => 3, 'reporting.coverage_bands.yellow_min' => 2]);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['exec'] = $this->user(null, RoleKey::Executive);
        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaAdmin);
        $this->users['partner'] = $this->user(null, RoleKey::DevelopmentPartner);

        $today = Carbon::today();

        // Registry — A: 4 (a child, a youth, an elderly-flagged, an unknown/no-DOB).
        $benA1 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse', 'ward' => 'zango', 'gender' => 'female', 'date_of_birth' => $today->copy()->subYears(10)->toDateString(), 'registration_source' => 'excel']);
        $benA2 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse', 'ward' => 'zango', 'gender' => 'male', 'date_of_birth' => $today->copy()->subYears(25)->toDateString(), 'registration_source' => 'api']);
        $benA3 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse', 'ward' => 'kachi', 'gender' => 'female', 'date_of_birth' => $today->copy()->subYears(70)->toDateString(), 'status' => 'flagged', 'registration_source' => 'excel']);
        Beneficiary::factory()->withoutNin()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse', 'ward' => 'kachi', 'gender' => null, 'date_of_birth' => null, 'registration_source' => 'excel']);

        // Registry — B: 1 adult, registered 60 days ago (outside "this period").
        $benB1 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'lga' => 'hadejia', 'ward' => 'yankwashi', 'gender' => 'male', 'date_of_birth' => $today->copy()->subYears(40)->toDateString(), 'registration_date' => $today->copy()->subDays(60)->toDateString()]);

        // One A beneficiary is in a household (the rest are individuals).
        HouseholdMembership::factory()->create(['beneficiary_id' => $benA1->id, 'household_id' => Household::factory()->create(['owner_mda_id' => $this->mdaA->id])->id]);

        // Programmes (global catalog); budget + target live on each MDA's activity.
        $this->progA = Programme::factory()->individual()->create(['status' => 'active']);
        $this->progB = Programme::factory()->individual()->create(['status' => 'active']);
        $actA = Activity::factory()->forProgramme($this->progA, $this->mdaA)->create(['name' => 'Cash round 1', 'budget_amount' => 1_000_000, 'involves_beneficiaries' => true, 'target_beneficiaries' => 4, 'starts_on' => '2026-01-01', 'ends_on' => '2026-12-31', 'funding_partner_id' => $this->users['partner']->id]);
        $actB = Activity::factory()->forProgramme($this->progB, $this->mdaB)->create(['name' => 'Feeding term 1', 'budget_amount' => 500_000, 'involves_beneficiaries' => true, 'target_beneficiaries' => 2, 'starts_on' => '2026-02-01', 'ends_on' => '2026-06-30']);
        ProgrammeFunder::create(['programme_id' => $this->progA->id, 'user_id' => $this->users['partner']->id]);

        // Benefits — NET vs GROSS: benA1 served TWICE (gross 2, net 1); benA2 once; benB1
        // once; benA3 (owned by A) served by B → a cross-MDA (joint) beneficiary. Each is
        // linked to its activity so the activity-level drill-down is exercised.
        $this->benefit($benA1, $this->progA, $this->mdaA, 100_000, 'dutse', $actA);
        $this->benefit($benA1, $this->progA, $this->mdaA, 100_000, 'dutse', $actA);
        $this->benefit($benA2, $this->progA, $this->mdaA, 200_000, 'dutse', $actA);
        $this->benefit($benB1, $this->progB, $this->mdaB, 150_000, 'hadejia', $actB);
        $this->benefit($benA3, $this->progB, $this->mdaB, 50_000, 'dutse', $actB); // cross-MDA serve

        // Coordination fixtures.
        Referral::create(['beneficiary_id' => $benA1->id, 'from_mda_id' => $this->mdaA->id, 'to_mda_id' => $this->mdaB->id, 'need' => 'Health', 'status' => 'created']);
        ServiceRequest::create(['beneficiary_id' => $benA3->id, 'from_mda_id' => $this->mdaB->id, 'to_mda_id' => $this->mdaA->id, 'status' => 'pending']);
        $accepted = ServiceRequest::create(['beneficiary_id' => $benA1->id, 'from_mda_id' => $this->mdaB->id, 'to_mda_id' => $this->mdaA->id, 'status' => 'accepted']);
        $accepted->forceFill(['created_at' => now()->subHours(5), 'decided_at' => now()])->save(); // 5h turnaround
        $this->syncRun($this->mdaA, 'completed');
        $this->syncRun($this->mdaA, 'failed');
        SyncConnector::factory()->create(['owner_mda_id' => $this->mdaA->id, 'source' => 'socu']);
        SyncConnector::factory()->create(['owner_mda_id' => $this->mdaA->id, 'source' => 'government_system']);
        $this->importRows($this->mdaA, $this->progA); // 2 matched rows

        app(DashboardSnapshotService::class)->refreshAll();
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda?->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function benefit(Beneficiary $b, Programme $p, Mda $mda, int $kobo, string $lga, ?Activity $activity = null): void
    {
        Benefit::factory()->create(['beneficiary_id' => $b->id, 'programme_id' => $p->id, 'mda_id' => $mda->id, 'activity_id' => $activity?->id, 'monetary_value' => $kobo, 'lga' => $lga, 'status' => 'verified']);
    }

    private function syncRun(Mda $mda, string $status): void
    {
        DB::table('sync_runs')->insert([
            'id' => (string) Str::uuid(), 'trigger' => 'scheduled', 'source' => 'socu',
            'owner_mda_id' => $mda->id, 'conflict_policy' => 'flag_for_review', 'status' => $status,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    private function importRows(Mda $mda, Programme $programme): void
    {
        $activity = Activity::factory()->forProgramme($programme, $mda)->create(['budget_amount' => 0, 'involves_beneficiaries' => false, 'target_beneficiaries' => null, 'starts_on' => '2026-03-01', 'ends_on' => '2026-09-30']);
        $batchId = (string) Str::uuid();
        DB::table('import_batches')->insert(['id' => $batchId, 'owner_mda_id' => $mda->id, 'original_filename' => 's.csv', 'stored_path' => 'imports/s.csv', 'source' => 'csv', 'activity_id' => $activity->id, 'status' => 'completed', 'created_at' => now(), 'updated_at' => now()]);
        foreach ([['exact', 'link'], ['probable', 'new']] as $i => [$band, $res]) {
            DB::table('import_rows')->insert(['id' => (string) Str::uuid(), 'import_batch_id' => $batchId, 'row_number' => $i + 1, 'payload' => json_encode([]), 'is_valid' => true, 'match_band' => $band, 'resolution' => $res, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    /** @return array<string, mixed> */
    private function metricsFor(string $key): array
    {
        return app(DashboardService::class)->forUser($this->users[$key])['metrics'];
    }

    /* --------------------------------------------------- net-unique vs gross (headline) */

    public function test_net_unique_served_is_distinct_from_the_gross_delivery_count(): void
    {
        $m = $this->metricsFor('exec');

        // Gross: 5 deliveries in the ledger. Net-unique: 4 distinct people served
        // (benA1 was served twice). The headline is the net figure.
        $this->assertSame(5, $m['benefits']['disbursed']['benefit_count']); // GROSS
        $this->assertSame(4, $m['population']['net_unique_served']);        // NET
        $this->assertLessThan($m['benefits']['disbursed']['benefit_count'], $m['population']['net_unique_served']);
    }

    public function test_population_uses_net_unique_registry_counts(): void
    {
        $m = $this->metricsFor('exec')['population'];

        $this->assertSame(5, $m['total_individuals']);       // deduplicated registry
        $this->assertSame(1, $m['total_households']);
        $this->assertSame(4, $m['net_unique_served']);
        $this->assertSame(4, $m['new_registrations_period']); // benB1 (60d ago) excluded
        $this->assertSame(2, $m['lgas_covered']);             // dutse + hadejia
        $this->assertSame(3, $m['wards_covered']);            // zango + kachi + yankwashi
    }

    public function test_programme_and_activity_counts_are_scoped(): void
    {
        $p = $this->metricsFor('exec')['programmes'];
        $this->assertSame(2, $p['total']);            // progA + progB
        $this->assertSame(2, $p['active']);
        $this->assertSame(3, $p['activities_total']); // progA + progB + progA import activity
        $this->assertSame(3, $p['activities_active']);

        // MDA A sees only its own activities (progA + the import activity).
        $a = $this->metricsFor('officerA')['programmes'];
        $this->assertSame(2, $a['activities_total']);
        $this->assertSame(2, $a['activities_active']);
    }

    /* ----------------------------------------------------------------- demographics */

    public function test_demographics_from_existing_fields(): void
    {
        $d = $this->metricsFor('exec')['demographics'];

        $this->assertSame(2, $d['by_gender']['female']);
        $this->assertSame(2, $d['by_gender']['male']);
        $this->assertSame(1, $d['by_gender']['unspecified']);
        $this->assertSame(0.5, $d['female_pct']); // 2 of 4 known

        $this->assertSame(['children' => 1, 'youth' => 1, 'adults' => 1, 'elderly' => 1, 'unknown' => 1], $d['age_bands']);
        $this->assertSame(1, $d['household_vs_individual']['in_household']);
        $this->assertSame(4, $d['household_vs_individual']['individual']);
    }

    public function test_household_size_distribution_bands_and_scope(): void
    {
        // A second household with 4 active members (band 4–6); benA1's household has 1.
        $hh = Household::factory()->create(['owner_mda_id' => $this->mdaA->id]);
        foreach (range(1, 4) as $i) {
            $member = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
            HouseholdMembership::factory()->create(['beneficiary_id' => $member->id, 'household_id' => $hh->id]);
        }
        app(DashboardSnapshotService::class)->refreshAll();

        $h = $this->metricsFor('exec')['household_size'];
        $this->assertSame(2, $h['total_households']);
        $this->assertSame(1, $h['bands']['1']);       // benA1's household
        $this->assertSame(0, $h['bands']['2-3']);
        $this->assertSame(1, $h['bands']['4-6']);     // the new 4-member household
        $this->assertEqualsWithDelta(2.5, $h['average_size'], 0.001); // (1 + 4) / 2

        // Partners own no households → empty distribution.
        $partner = $this->metricsFor('partner')['household_size'];
        $this->assertSame(0, $partner['total_households']);
        $this->assertNull($partner['average_size']);
    }

    /* ---------------------------------------------------------- programme performance */

    public function test_programme_performance_target_reached_budget_and_traffic_light(): void
    {
        $rows = collect($this->metricsFor('exec')['programme_performance'])->keyBy('programme_id');

        $a = $rows[$this->progA->id];
        $this->assertSame(4, $a['target']);
        $this->assertSame(2, $a['reached']);          // net-unique served in A
        $this->assertSame(0.5, $a['completion_rate']);
        $this->assertSame(1_000_000, $a['budget']['allocated']);
        $this->assertSame(400_000, $a['budget']['spent']);
        $this->assertSame(200_000, $a['cost_per_beneficiary']); // 400k ÷ 2
        $this->assertSame('yellow', $a['traffic_light']);       // 0.5 ≥ yellow, < green

        $b = $rows[$this->progB->id];
        $this->assertEquals(1.0, $b['completion_rate']);        // reached 2 of 2 (JSON: 1)
        $this->assertSame('green', $b['traffic_light']);
    }

    public function test_programme_performance_carries_status_mdas_dates_and_scoring(): void
    {
        $a = collect($this->metricsFor('exec')['programme_performance'])->firstWhere('programme_id', $this->progA->id);

        $this->assertSame('active', $a['status']);
        $this->assertCount(1, $a['mdas']);                 // both A activities owned by MDA A
        $this->assertSame('MDA A', $a['mdas'][0]['name']);
        $this->assertSame('2026-01-01', $a['start_date']); // earliest activity start
        $this->assertSame('2026-12-31', $a['end_date']);   // latest activity end

        // Configurable traffic-light thresholds are exposed for the legend.
        $scoring = $this->metricsFor('exec')['programme_scoring'];
        $this->assertSame(0.8, $scoring['green_min']);
        $this->assertSame(0.5, $scoring['yellow_min']);
    }

    public function test_programme_drilldown_to_activity_level_is_scoped(): void
    {
        $a = collect($this->metricsFor('exec')['programme_performance'])->firstWhere('programme_id', $this->progA->id);

        // progA has two scoped activities: the cash round + the (no-beneficiary) import activity.
        $this->assertCount(2, $a['activities']);

        $cash = collect($a['activities'])->firstWhere('name', 'Cash round 1');
        $this->assertSame('MDA A', $cash['mda']);
        $this->assertSame(4, $cash['target']);
        $this->assertSame(2, $cash['reached']);            // benA1 (net 1) + benA2
        $this->assertSame(0.5, $cash['completion_rate']);
        $this->assertSame('yellow', $cash['traffic_light']);
        $this->assertSame(1_000_000, $cash['budget']['allocated']);
        $this->assertSame(400_000, $cash['budget']['spent']);
        $this->assertSame(200_000, $cash['cost_per_beneficiary']);

        // The import activity involves no beneficiaries → unrated, nothing reached.
        $import = collect($a['activities'])->firstWhere('reached', 0);
        $this->assertSame('unrated', $import['traffic_light']);
        $this->assertNull($import['completion_rate']);

        // Scope: an MDA-A officer sees A's activities; a partner sees the funded programme's.
        $officer = collect($this->metricsFor('officerA')['programme_performance'])->firstWhere('programme_id', $this->progA->id);
        $this->assertNotEmpty($officer['activities']);
        $partner = collect($this->metricsFor('partner')['programme_performance'])->firstWhere('programme_id', $this->progA->id);
        $this->assertSame('Cash round 1', collect($partner['activities'])->firstWhere('name', 'Cash round 1')['name']);
    }

    /* ------------------------------------------------------------- registry quality */

    public function test_registry_quality_status_completeness_and_dedup(): void
    {
        $q = $this->metricsFor('exec')['registry_quality'];

        $this->assertSame(4, $q['verified']);   // active
        $this->assertSame(1, $q['pending']);    // flagged
        $this->assertSame(0.8, $q['nin_completeness']); // 4 of 5 (one withoutNin)
        $this->assertSame(2, $q['duplicates_detected']);
        $this->assertIsFloat($q['data_completeness']);
        $this->assertLessThanOrEqual(1.0, $q['data_completeness']);
    }

    /* ---------------------------------------------------------------- coordination */

    public function test_coordination_agencies_joint_partners_and_sync(): void
    {
        $c = $this->metricsFor('exec')['coordination'];

        $this->assertSame(2, $c['active_mdas']);
        $this->assertSame(1, $c['cross_mda_beneficiaries']); // benA3 (A-owned) served by B
        $this->assertSame(1, $c['referral_throughput']['total']);
        $this->assertSame(2, $c['request_to_serve']['raised']);
        $this->assertSame(1, $c['request_to_serve']['pending']);
        $this->assertSame(1, $c['request_to_serve']['accepted']);

        $this->assertSame(0, $c['joint_programmes']); // no programme is run by ≥2 MDAs here

        // Request-to-serve approval rate + turnaround.
        $this->assertEquals(1.0, $c['request_to_serve']['approval_rate']); // 1 accepted, 0 declined
        $this->assertEqualsWithDelta(5.0, $c['request_to_serve']['avg_turnaround_hours'], 0.05);

        // Partner contributions — aggregate AND per-partner list, scoped to funded programmes.
        $this->assertSame(1, $c['partners']['count']);
        $this->assertSame(1, $c['partners']['funded_programmes']);
        $this->assertSame(2, $c['partners']['beneficiaries_served']);   // net-unique in progA
        $this->assertSame(1_000_000, $c['partners']['funding_allocated']);
        $this->assertCount(1, $c['partners']['list']);
        $this->assertSame(1, $c['partners']['list'][0]['funded_programmes']);
        $this->assertSame(2, $c['partners']['list'][0]['beneficiaries_served']);
        $this->assertSame(1_000_000, $c['partners']['list'][0]['funding_allocated']);

        $this->assertSame(2, $c['sync_health']['total_runs']);
        $this->assertSame(1, $c['sync_health']['succeeded']);
        $this->assertSame(1, $c['sync_health']['failed']);
        $this->assertSame(1, $c['sync_health']['api_registrations']); // benA2 source=api
        $this->assertSame(2, $c['sync_health']['connectors']);
        $this->assertEqualsCanonicalizing(['socu', 'government_system'], $c['sync_health']['sources']);
    }

    public function test_joint_programmes_counts_multi_mda_programmes(): void
    {
        // Give progA a second implementing MDA → it becomes a joint (cross-MDA) programme.
        Activity::factory()->forProgramme($this->progA, $this->mdaB)->create(['budget_amount' => 0, 'target_beneficiaries' => 1]);
        app(DashboardSnapshotService::class)->refreshAll();

        $this->assertSame(1, $this->metricsFor('exec')['coordination']['joint_programmes']);
    }

    public function test_coordination_is_hidden_from_partners(): void
    {
        $this->assertNull($this->metricsFor('partner')['coordination']);
    }

    /* ------------------------------------------------------------- coverage banding */

    public function test_coverage_bands_are_absolute_not_population_pct(): void
    {
        $cb = $this->metricsFor('exec')['coverage_bands'];

        $this->assertSame('absolute', $cb['basis']);
        // dutse = 4 (≥ 3 → green), hadejia = 1 (< 2 → red).
        $this->assertSame(1, $cb['summary']['green']);
        $this->assertSame(1, $cb['summary']['red']);
        $this->assertSame(0, $cb['summary']['yellow']);
    }

    /* -------------------------------------------------------------------- trends */

    public function test_trends_are_periodised_and_zero_filled(): void
    {
        $t = $this->metricsFor('exec')['trends'];

        $this->assertCount(12, $t['months']);
        $this->assertCount(12, $t['disbursement']);
        // All deliveries are this month → the last bucket carries the full 600k.
        $this->assertSame(600_000, end($t['disbursement'])['value']);
        // The 4 A registrations are this month; benB1 was 2 months ago.
        $this->assertSame(4, end($t['registrations'])['value']);
    }

    /* ------------------------------------------------------------- deferred slots */

    public function test_deferred_slots_exist_but_return_nothing(): void
    {
        $deferred = $this->metricsFor('exec')['deferred'];

        foreach (['population_penetration', 'targeting_accuracy', 'vulnerability_categories', 'outcome_indicators', 'identity_verification'] as $slot) {
            $this->assertArrayHasKey($slot, $deferred);
            $this->assertNull($deferred[$slot]);
        }
    }

    /* ---------------------------------------------------------------------- scope */

    public function test_metrics_are_scoped_per_caller(): void
    {
        // MDA A: its own 4 individuals; net-unique served BY A is 2 (benA1, benA2) —
        // benA3 was served by B, so it is not in A's delivery scope.
        $a = $this->metricsFor('officerA');
        $this->assertSame(4, $a['population']['total_individuals']);
        $this->assertSame(2, $a['population']['net_unique_served']);
        $this->assertCount(1, $a['programme_performance']); // only programme A
        $this->assertSame($this->progA->id, $a['programme_performance'][0]['programme_id']);

        // Partner: funded programme A only; coordination does not apply; deferred present.
        $p = $this->metricsFor('partner');
        $this->assertNull($p['coordination']);
        $this->assertSame(2, $p['population']['net_unique_served']); // served by progA
        $this->assertArrayHasKey('identity_verification', $p['deferred']);
    }
}
