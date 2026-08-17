<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use App\Domain\Reporting\Services\DashboardMetricsService;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use App\Domain\Reporting\Services\DashboardService;
use App\Domain\Reporting\Services\DashboardSnapshotService;
use App\Domain\Reporting\Support\DashboardFilter;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Phase 6P — partner→activity funding attribution + partner-scoped aggregates. An
 * activity resolves to its funding partner; partner-funding figures are activity-precise
 * (allocated/delivered/remaining), labelled as DELIVERED VALUE (not expenditure); a
 * partner sees ONLY their funded data; and programme overlap (same programme + LGA
 * across different funders/MDAs) is detected.
 */
class PartnerFundingTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Programme $shared;

    private Programme $progA;

    private Programme $progB;

    private Activity $actA1;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['partnerA'] = $this->user(null, RoleKey::DevelopmentPartner);
        $this->users['partnerB'] = $this->user(null, RoleKey::DevelopmentPartner);
        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaAdmin);

        $this->shared = Programme::factory()->individual()->create(['status' => 'active']);
        $this->progA = Programme::factory()->individual()->create(['status' => 'active']);
        $this->progB = Programme::factory()->individual()->create(['status' => 'active']);

        // Shared programme funded by BOTH partners in the SAME LGA via different MDAs → overlap.
        $this->actA1 = $this->activity($this->shared, $this->mdaA, 'dutse', 1_000_000, 4, 'partnerA');
        $actB1 = $this->activity($this->shared, $this->mdaB, 'dutse', 500_000, 2, 'partnerB');
        // Sole-funded activities.
        $actA2 = $this->activity($this->progA, $this->mdaA, 'hadejia', 2_000_000, 3, 'partnerA');
        $actB2 = $this->activity($this->progB, $this->mdaB, 'gumel', 800_000, 2, 'partnerB');

        // benA1: a woman + a child, in a household; benA2: an adult man. (Deterministic reach.)
        $benA1 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse', 'gender' => 'female', 'date_of_birth' => Carbon::today()->subYears(10)->toDateString()]);
        $benA2 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'hadejia', 'gender' => 'male', 'date_of_birth' => Carbon::today()->subYears(40)->toDateString()]);
        $benB1 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'lga' => 'dutse']);
        $benB2 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'lga' => 'gumel']);
        HouseholdMembership::factory()->create(['beneficiary_id' => $benA1->id, 'household_id' => Household::factory()->create(['owner_mda_id' => $this->mdaA->id])->id]);

        $this->deliver($benA1, $this->shared, $this->mdaA, $this->actA1, 'dutse', 300_000);
        $this->deliver($benA2, $this->progA, $this->mdaA, $actA2, 'hadejia', 500_000);
        $this->deliver($benB1, $this->shared, $this->mdaB, $actB1, 'dutse', 200_000);
        $this->deliver($benB2, $this->progB, $this->mdaB, $actB2, 'gumel', 400_000);

        app(DashboardSnapshotService::class)->refreshAll();
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda?->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function activity(Programme $p, Mda $mda, string $lga, int $budget, int $target, string $partnerKey): Activity
    {
        return Activity::factory()->forProgramme($p, $mda)->inLgaCode($lga)->create([
            'status' => 'active', 'budget_amount' => $budget,
            'target_beneficiaries' => $target, 'funding_partner_id' => $this->users[$partnerKey]->id,
        ]);
    }

    private function deliver(Beneficiary $b, Programme $p, Mda $mda, Activity $a, string $lga, int $kobo): void
    {
        Benefit::factory()->create([
            'beneficiary_id' => $b->id, 'programme_id' => $p->id, 'mda_id' => $mda->id, 'activity_id' => $a->id,
            'lga' => $lga, 'monetary_value' => $kobo, 'status' => 'verified',
        ]);
    }

    /** @return array<string, mixed> */
    private function funding(string $partnerKey): array
    {
        return app(DashboardService::class)->forUser($this->users[$partnerKey])['metrics']['partner_funding'];
    }

    /** Live compute for a partner not in the shared snapshot (new fixtures added mid-test). */
    private function computeFunding(User $partner): array
    {
        $scope = app(DashboardScopeResolver::class)->forUser($partner);

        return app(DashboardMetricsService::class)->compute($scope)['partner_funding'];
    }

    private function fundedActivity(Programme $p, User $partner, int $budget, int $target, ?string $endsOn = null): Activity
    {
        $attrs = [
            'status' => 'active', 'budget_amount' => $budget,
            'target_beneficiaries' => $target, 'funding_partner_id' => $partner->id,
        ];
        if ($endsOn !== null) {
            $attrs['ends_on'] = $endsOn;
        }

        return Activity::factory()->forProgramme($p, $this->mdaA)->inLgaCode('dutse')->create($attrs);
    }

    private function deliverTyped(Beneficiary $b, Activity $a, string $type, int $kobo = 100_000): void
    {
        Benefit::factory()->create([
            'beneficiary_id' => $b->id, 'programme_id' => $a->programme_id, 'mda_id' => $this->mdaA->id,
            'activity_id' => $a->id, 'lga' => 'dutse', 'benefit_type' => $type,
            'monetary_value' => $kobo, 'status' => 'verified',
        ]);
    }

    private function enroll(Beneficiary $b, Activity $a): void
    {
        Enrollment::factory()->create([
            'programme_id' => $a->programme_id, 'activity_id' => $a->id, 'mda_id' => $this->mdaA->id,
            'beneficiary_id' => $b->id, 'status' => 'enrolled',
        ]);
    }

    /* ------------------------------------------------------------- attribution */

    public function test_an_activity_resolves_to_its_funding_partner(): void
    {
        $this->assertSame($this->users['partnerA']->id, $this->actA1->fresh()->funding_partner_id);
        $this->assertSame($this->users['partnerA']->id, $this->actA1->fundingPartner->id);

        // The resolver derives the partner's FUNDED programmes from the attribution.
        $scope = app(DashboardScopeResolver::class)->forUser($this->users['partnerA']);
        $this->assertSame('partner', $scope->kind);
        $this->assertEqualsCanonicalizing([$this->shared->id, $this->progA->id], $scope->programmeIds);
        $this->assertNotContains($this->progB->id, $scope->programmeIds ?? []);
    }

    /* ------------------------------------------------- activity-precise figures */

    public function test_partner_funding_is_activity_precise_and_labelled_as_delivered_value(): void
    {
        $f = $this->funding('partnerA');

        $this->assertSame(3_000_000, $f['allocated']);       // 1M + 2M funded activities
        $this->assertSame(800_000, $f['delivered_value']);   // 300k + 500k delivered
        $this->assertSame(2_200_000, $f['remaining']);
        $this->assertEqualsWithDelta(0.2667, $f['utilization_rate'], 0.0001);
        $this->assertSame(2, $f['funded_programmes']);
        $this->assertSame(2, $f['funded_activities']);
        $this->assertSame(1, $f['implementing_mdas']);
        $this->assertSame(2, $f['net_unique_reached']);
        $this->assertSame(7, $f['target']);
        $this->assertEqualsWithDelta(0.2857, $f['reach_vs_target'], 0.0001);
        $this->assertSame(400_000, $f['cost_per_beneficiary']); // 800k ÷ 2

        // Coverage + activity counts (funded scope).
        $this->assertSame(2, $f['active_activities']);
        $this->assertSame(2, $f['lgas_covered']);   // dutse + hadejia
        $this->assertSame(1, $f['wards_covered']);

        // Reach demographics of the served cohort (captured fields only).
        $this->assertSame(1, $f['reach']['women_reached']);      // benA1
        $this->assertSame(1, $f['reach']['children_reached']);   // benA1 (age 10)
        $this->assertSame(1, $f['reach']['households_reached']);  // benA1's household

        // Labelled as DELIVERED VALUE — never treasury "spent"/"expenditure".
        $this->assertArrayHasKey('delivered_value', $f);
        $this->assertArrayNotHasKey('spent', $f);
        $this->assertArrayNotHasKey('expenditure', $f);
    }

    /* ------------------------------------------------------- funded-scope only */

    public function test_a_partner_sees_only_their_own_funded_data(): void
    {
        $a = $this->funding('partnerA');
        $b = $this->funding('partnerB');

        // Partner A's figures exclude Partner B's funding entirely.
        $this->assertSame(3_000_000, $a['allocated']);
        $this->assertSame(1_300_000, $b['allocated']); // 500k + 800k — disjoint from A
        $this->assertSame(2, $a['net_unique_reached']);
        $this->assertSame(2, $b['net_unique_reached']);

        // funding_by_partner lists ONLY the caller (no other partner's money leaks).
        $this->assertCount(1, $a['funding_by_partner']);
        $this->assertSame($this->users['partnerA']->id, $a['funding_by_partner'][0]['partner_id']);
        $this->assertSame(3_000_000, $a['funding_by_partner'][0]['allocated']);

        // A programme the partner does NOT fund is out of scope.
        $programmes = collect(app(DashboardService::class)->forUser($this->users['partnerA'])['metrics']['programme_performance'])
            ->pluck('programme_id')->all();
        $this->assertNotContains($this->progB->id, $programmes);
    }

    /* ---------------------------------------------------------- programme overlap */

    public function test_programme_overlap_is_detected(): void
    {
        $overlap = $this->funding('partnerA')['programme_overlap'];

        $this->assertSame(1, $overlap['count']); // only the shared programme × dutse overlaps
        $cell = $overlap['cells'][0];
        $this->assertSame($this->shared->id, $cell['programme_id']);
        $this->assertSame('Dutse', $cell['lga']); // resolved LGA name, not the raw id
        $this->assertSame(1, $cell['other_funders']); // partnerB
        $this->assertSame(1, $cell['other_mdas']);    // MDA B

        // The sole-funded programme/LGA does NOT overlap.
        $this->assertNotContains($this->progA->id, array_column($overlap['cells'], 'programme_id'));
    }

    /* --------------------------------------------------- attribution via the API */

    public function test_attribution_is_set_and_validated_on_activity_edit(): void
    {
        $token = $this->users['officerA']->createToken('t')->plainTextToken;

        // The owning MDA can (re)attribute the activity to a Development Partner.
        $this->withToken($token)->patchJson("/api/v1/activities/{$this->actA1->id}", [
            'funding_partner_id' => $this->users['partnerB']->id,
        ])->assertOk()->assertJsonPath('data.funding_partner_id', $this->users['partnerB']->id);

        // A non–Development-Partner user is rejected.
        $this->withToken($token)->patchJson("/api/v1/activities/{$this->actA1->id}", [
            'funding_partner_id' => $this->users['officerA']->id,
        ])->assertStatus(422)->assertJsonPath('error.code', 'VALIDATION_ERROR');
    }

    /* -------------------------------------------- programmes & results (tab 2) */

    public function test_funded_programme_cards_are_activity_precise_and_labelled_delivered_value(): void
    {
        $programmes = collect($this->funding('partnerA')['programmes'])->keyBy('programme_id');
        $this->assertCount(2, $programmes); // only the partner's two funded programmes

        // Shared programme — ONLY partner A's funded activity (actA1) counts, not MDA B's.
        $shared = $programmes[$this->shared->id];
        $this->assertSame(1_000_000, $shared['allocated']);      // actA1 budget only (not actB1's 500k)
        $this->assertSame(300_000, $shared['delivered_value']);  // delivery value, not spend
        $this->assertSame(700_000, $shared['remaining']);
        $this->assertSame(4, $shared['target']);
        $this->assertSame(1, $shared['reached']);
        $this->assertSame(1, $shared['coverage_absolute']);
        $this->assertEqualsWithDelta(0.25, $shared['completion_rate'], 0.0001);
        $this->assertSame(1, $shared['interventions']);          // one benefit record
        $this->assertSame(300_000, $shared['avg_benefit_value']);
        $this->assertSame(300_000, $shared['cost_per_beneficiary']);
        $this->assertSame('delayed', $shared['status_light']);   // 0.25 completion, in timeline
        $this->assertCount(1, $shared['mdas']);
        $this->assertSame('MDA A', $shared['mdas'][0]['name']);

        // Financials are labelled DELIVERED VALUE — never treasury "spent".
        $this->assertArrayHasKey('delivered_value', $shared);
        $this->assertArrayNotHasKey('spent', $shared);
        $this->assertArrayNotHasKey('budget', $shared);

        // A monthly delivery-rate series is present for the burn chart.
        $this->assertNotEmpty($shared['delivery_series']);
        $this->assertArrayHasKey('month', $shared['delivery_series'][0]);
        $this->assertArrayHasKey('value', $shared['delivery_series'][0]);

        // Activity drill-down is activity-precise (delivered value, reach).
        $this->assertCount(1, $shared['activities']);
        $this->assertSame($this->actA1->id, $shared['activities'][0]['activity_id']);
        $this->assertSame(300_000, $shared['activities'][0]['delivered_value']);
        $this->assertSame(1, $shared['activities'][0]['reached']);

        // The sole-funded programme is activity-precise too.
        $progA = $programmes[$this->progA->id];
        $this->assertSame(2_000_000, $progA['allocated']);
        $this->assertSame(500_000, $progA['delivered_value']);
        $this->assertSame(1_500_000, $progA['remaining']);
    }

    public function test_output_indicators_count_interventions_by_type_and_demographic(): void
    {
        $partner = $this->user(null, RoleKey::DevelopmentPartner);
        $prog = Programme::factory()->individual()->create(['status' => 'active']);
        $act = $this->fundedActivity($prog, $partner, 1_000_000, 10);

        // Served cohort: 2 adult women, 1 female child (counts as woman AND child), 1 adult man.
        $woman1 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'gender' => 'female', 'date_of_birth' => Carbon::today()->subYears(30)->toDateString()]);
        $woman2 = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'gender' => 'female', 'date_of_birth' => Carbon::today()->subYears(45)->toDateString()]);
        $girl = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'gender' => 'female', 'date_of_birth' => Carbon::today()->subYears(8)->toDateString()]);
        $man = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'gender' => 'male', 'date_of_birth' => Carbon::today()->subYears(40)->toDateString()]);

        // cash: 5 interventions across 4 distinct people (woman1 served twice); food: 1 (the girl).
        $this->deliverTyped($woman1, $act, 'cash');
        $this->deliverTyped($woman1, $act, 'cash'); // repeat delivery — an extra intervention, same person
        $this->deliverTyped($woman2, $act, 'cash');
        $this->deliverTyped($girl, $act, 'cash');
        $this->deliverTyped($man, $act, 'cash');
        $this->deliverTyped($girl, $act, 'food');

        $pf = $this->computeFunding($partner);
        $byType = collect($pf['output_indicators'])->keyBy('benefit_type');

        // OUTPUTS ONLY — interventions (records) counted, with captured demographics.
        $cash = $byType['cash'];
        $this->assertSame(5, $cash['interventions']);  // 5 benefit records
        $this->assertSame(4, $cash['beneficiaries']);  // 4 distinct people
        $this->assertSame(3, $cash['women']);          // woman1, woman2, girl
        $this->assertSame(1, $cash['children']);       // the girl

        $food = $byType['food'];
        $this->assertSame(1, $food['interventions']);
        $this->assertSame(1, $food['beneficiaries']);
        $this->assertSame(1, $food['women']);
        $this->assertSame(1, $food['children']);

        // Same figures appear on the single funded programme's own breakdown.
        $progOutputs = collect($pf['programmes'])->firstWhere('programme_id', $prog->id)['output_indicators'];
        $this->assertSame(5, collect($progOutputs)->firstWhere('benefit_type', 'cash')['interventions']);

        // OUTCOMES are never fabricated — no poverty/income/attendance keys leak in.
        foreach ($pf['output_indicators'] as $row) {
            $this->assertArrayNotHasKey('poverty_reduction', $row);
            $this->assertArrayNotHasKey('income', $row);
        }
    }

    public function test_programme_delivery_status_reflects_completion_and_timeline(): void
    {
        $partner = $this->user(null, RoleKey::DevelopmentPartner);
        $yesterday = Carbon::yesterday()->toDateString();

        // completed: past end date + completion ≥ 0.9 (2/2).
        $done = Programme::factory()->individual()->create(['status' => 'active']);
        $doneAct = $this->fundedActivity($done, $partner, 1_000_000, 2, $yesterday);
        // on track: in timeline + completion ≥ 0.8 (4/5).
        $onTrack = Programme::factory()->individual()->create(['status' => 'active']);
        $onTrackAct = $this->fundedActivity($onTrack, $partner, 1_000_000, 5);
        // at risk: in timeline + 0.5 ≤ completion < 0.8 (2/4).
        $atRisk = Programme::factory()->individual()->create(['status' => 'active']);
        $atRiskAct = $this->fundedActivity($atRisk, $partner, 1_000_000, 4);
        // delayed: in timeline + completion < 0.5 (1/10).
        $delayed = Programme::factory()->individual()->create(['status' => 'active']);
        $delayedAct = $this->fundedActivity($delayed, $partner, 1_000_000, 10);

        $serve = function (Activity $a, int $count): void {
            for ($i = 0; $i < $count; $i++) {
                $b = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]);
                $this->deliverTyped($b, $a, 'cash');
            }
        };
        $serve($doneAct, 2);
        $serve($onTrackAct, 4);
        $serve($atRiskAct, 2);
        $serve($delayedAct, 1);

        $status = collect($this->computeFunding($partner)['programmes'])
            ->keyBy('programme_id')->map(fn (array $p): string => $p['status_light']);

        $this->assertSame('completed', $status[$done->id]);
        $this->assertSame('on_track', $status[$onTrack->id]);
        $this->assertSame('at_risk', $status[$atRisk->id]);
        $this->assertSame('delayed', $status[$delayed->id]);
    }

    /* -------------------------------------- cross-cutting: filters, export, PII */

    public function test_a_partner_filter_can_only_narrow_within_funded_scope(): void
    {
        $scope = app(DashboardScopeResolver::class)->forUser($this->users['partnerA']);
        $metrics = app(DashboardMetricsService::class);

        // Filtering to a programme the partner does NOT fund yields nothing — a filter can
        // only ever NARROW within funded scope, never widen it.
        $outside = $metrics->compute($scope, new DashboardFilter(programmeId: $this->progB->id))['partner_funding'];
        $this->assertSame(0, $outside['allocated']);
        $this->assertSame([], $outside['programmes']);

        // Filtering to a funded programme narrows to just it.
        $inside = $metrics->compute($scope, new DashboardFilter(programmeId: $this->progA->id))['partner_funding'];
        $this->assertSame(2_000_000, $inside['allocated']); // actA2 only
        $this->assertSame(1, $inside['funded_programmes']);
    }

    public function test_partner_export_is_aggregate_only_never_pii(): void
    {
        $partner = $this->users['partnerA'];

        // Tiering: aggregate reporting export is allowed; raw-registry / PII exports are not.
        $this->assertTrue($partner->hasPermission('reporting.export'));
        $this->assertFalse($partner->hasPermission('beneficiary.export'));
        $this->assertFalse($partner->hasPermission('export.reveal_pii'));

        $this->withToken($partner->createToken('exp')->plainTextToken)
            ->get('/api/v1/dashboard/export?format=csv')->assertOk();

        // The raw-registry export route is forbidden for a partner.
        $this->app['auth']->forgetGuards();
        $this->withToken($partner->createToken('exp2')->plainTextToken)
            ->get('/api/v1/beneficiaries/export')->assertStatus(403);
    }

    public function test_partner_dashboard_exposes_no_raw_pii(): void
    {
        $served = Beneficiary::query()->withoutGlobalScopes()->where('owner_mda_id', $this->mdaA->id)->firstOrFail();
        $json = (string) json_encode(app(DashboardService::class)->forUser($this->users['partnerA']));

        // No serialized-beneficiary PII keys leak (aggregate metrics never carry these;
        // note `missing.date_of_birth`/`missing.nin` are quality COUNTS, not PII, so they
        // are deliberately not in this list).
        foreach (['first_name', 'last_name', 'block_name_dob', 'nin_hash', 'bvn_hash'] as $key) {
            $this->assertStringNotContainsString($key, $json);
        }
        // No raw PII VALUES (a served beneficiary's phone number + surname).
        if ($served->phone !== null) {
            $this->assertStringNotContainsString($served->phone, $json);
        }
        $this->assertStringNotContainsString($served->last_name, $json);
    }

    /* ------------------------------------------------ coordination (tab 4) */

    public function test_partner_coordination_landscape_and_funding_by_partner(): void
    {
        $c = $this->funding('partnerA')['coordination'];

        // Partner landscape — actors around the funded programmes.
        $this->assertSame(2, $c['landscape']['funders']);               // partnerA + partnerB
        $this->assertSame(2, $c['landscape']['government_agencies']);   // MDA A + MDA B run activities in these programmes
        $this->assertSame(1, $c['landscape']['implementing_agencies']); // only MDA A delivers on partnerA's funded activities

        // Funding-by-partner — amounts for the CALLER only; a co-funder's money never leaks.
        $byPartner = collect($c['funding_by_partner'])->keyBy('partner_id');
        $self = $byPartner[$this->users['partnerA']->id];
        $this->assertTrue($self['is_self']);
        $this->assertSame(3_000_000, $self['allocated']);
        $this->assertSame(800_000, $self['delivered_value']);
        $this->assertSame(2, $self['net_unique_reached']);
        $this->assertSame(2, $self['funded_programmes']);

        $coFunder = $byPartner[$this->users['partnerB']->id];
        $this->assertFalse($coFunder['is_self']);
        $this->assertNull($coFunder['allocated']);        // no money leak
        $this->assertNull($coFunder['delivered_value']);
        $this->assertNull($coFunder['net_unique_reached']);
        $this->assertSame(1, $coFunder['shared_programmes']); // the shared programme

        // Government-agency (MDA) landscape — counts only, never money.
        $agencies = collect($c['agencies'])->keyBy('id');
        $this->assertSame(2, $agencies[$this->mdaA->id]['activities']); // actA1 + actA2
        $this->assertSame(1, $agencies[$this->mdaB->id]['activities']); // actB1
        $this->assertArrayNotHasKey('allocated', $agencies[$this->mdaA->id]);

        // Programme overlap (the tab's headline) is detected on the same block.
        $this->assertSame(1, $this->funding('partnerA')['programme_overlap']['count']);

        // Omitted modules are absent from the payload (inert slots only, rendered client-side).
        $this->assertArrayNotHasKey('meetings', $c);
        $this->assertArrayNotHasKey('reporting_compliance', $c);
    }

    /* -------------------------------------------- registry (funded cohort, tab 3) */

    public function test_partner_registry_reports_funded_cohort_kpis_funnel_and_quality(): void
    {
        $partner = $this->user(null, RoleKey::DevelopmentPartner);
        $prog = Programme::factory()->individual()->create(['status' => 'active']);
        $act = $this->fundedActivity($prog, $partner, 1_000_000, 10);

        // Funded cohort: served+enrolled woman (has NIN, in a household); served-only man
        // (no NIN); enrolled-only child (registered + enrolled but NOT yet receiving).
        $servedEnrolled = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'gender' => 'female', 'lga' => 'dutse', 'date_of_birth' => Carbon::today()->subYears(30)->toDateString()]);
        $servedOnly = Beneficiary::factory()->withoutNin()->withoutBvn()->create(['owner_mda_id' => $this->mdaA->id, 'gender' => 'male', 'lga' => 'dutse', 'date_of_birth' => Carbon::today()->subYears(40)->toDateString()]);
        $enrolledOnly = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'gender' => 'female', 'lga' => 'hadejia', 'date_of_birth' => Carbon::today()->subYears(8)->toDateString()]);

        HouseholdMembership::factory()->create(['beneficiary_id' => $servedEnrolled->id, 'household_id' => Household::factory()->create(['owner_mda_id' => $this->mdaA->id])->id]);

        $this->enroll($servedEnrolled, $act);
        $this->enroll($enrolledOnly, $act);
        $this->deliverTyped($servedEnrolled, $act, 'cash');
        $this->deliverTyped($servedOnly, $act, 'cash');

        $reg = $this->computeFunding($partner)['registry'];

        // Funded-scope registry KPIs (aggregate, no PII).
        $this->assertSame(3, $reg['total_individuals']);
        $this->assertSame(1, $reg['total_households']);
        $this->assertSame(3, $reg['verified']);       // all active
        $this->assertSame(0, $reg['pending']);
        $this->assertSame(3, $reg['new_registrations']);
        $this->assertSame(0, $reg['updated_records']); // freshly created (updated_at == created_at)
        $this->assertSame(0, $reg['duplicate_records']); // no import matches in this fixture

        // Reduced targeting funnel — the stages we HAVE; the eligible→selected steps are absent.
        $this->assertSame(3, $reg['funnel']['registered']);
        $this->assertSame(2, $reg['funnel']['enrolled']);  // servedEnrolled + enrolledOnly
        $this->assertSame(2, $reg['funnel']['receiving']); // servedEnrolled + servedOnly
        $this->assertArrayNotHasKey('eligible', $reg['funnel']);
        $this->assertArrayNotHasKey('selected', $reg['funnel']);

        // Demographics — captured fields only.
        $this->assertSame(2, $reg['demographics']['by_gender']['female']);
        $this->assertSame(1, $reg['demographics']['by_gender']['male']);
        $this->assertSame(1, $reg['demographics']['age_bands']['children']); // the 8-year-old
        $this->assertSame(0, $reg['demographics']['age_bands']['unknown']);
        $this->assertSame(2, $reg['demographics']['by_lga']['dutse']);
        $this->assertSame(1, $reg['demographics']['household_size']['total_households']);
        $this->assertArrayNotHasKey('poverty', $reg['demographics']);
        $this->assertArrayNotHasKey('disability', $reg['demographics']);
        $this->assertArrayNotHasKey('vulnerability', $reg['demographics']);

        // Data quality.
        $this->assertEqualsWithDelta(1.0, $reg['quality']['verification_rate'], 0.0001);
        $this->assertEqualsWithDelta(0.6667, $reg['quality']['nin_linkage'], 0.0001); // 2 of 3 have NIN
        $this->assertSame(1, $reg['quality']['missing']['nin']);                        // servedOnly
        $this->assertNotNull($reg['quality']['data_completeness']);
        // SP-MIS is not a payment engine — no bank/mobile-money verification field.
        $this->assertArrayNotHasKey('bank_verified', $reg['quality']);
        $this->assertArrayNotHasKey('mobile_money_verified', $reg['quality']);
    }
}
