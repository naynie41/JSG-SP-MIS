<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Models\ProgrammeFunder;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Services\DashboardService;
use App\Domain\Reporting\Services\DashboardSnapshotService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Dashboard aggregation layer (PRD FR-RPT-01/02, FR-DSH-01): correct totals per
 * scope, scope enforcement (Executive state-wide / MDA own / Partner funded), and
 * that reads come from the summary snapshot, not a raw scan.
 */
class DashboardMetricsTest extends TestCase
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

        $this->users['exec'] = $this->user(null, RoleKey::Executive);          // state-wide
        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);
        $this->users['partner'] = $this->user(null, RoleKey::DevelopmentPartner);

        // Registry: 3 beneficiaries in A (one suspended), 2 in B.
        $aBens = Beneficiary::factory()->count(3)->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse', 'registration_source' => 'excel']);
        $aBens[2]->update(['status' => 'suspended']);
        $bBens = Beneficiary::factory()->count(2)->create(['owner_mda_id' => $this->mdaB->id, 'lga' => 'hadejia', 'registration_source' => 'kobo']);

        // Programmes are a global catalog (§10); budget lives on each MDA's activity.
        // A runs programme A (budget 1,000,000 kobo); B runs programme B (500,000).
        $this->progA = Programme::factory()->individual()->create(['status' => 'active']);
        $this->progB = Programme::factory()->individual()->create(['status' => 'active']);
        ProgrammeFunder::create(['programme_id' => $this->progA->id, 'user_id' => $this->users['partner']->id]);
        Activity::factory()->forProgramme($this->progA, $this->mdaA)->create(['budget_amount' => 1_000_000]);
        Activity::factory()->forProgramme($this->progB, $this->mdaB)->create(['budget_amount' => 500_000]);

        // Benefits: A delivers 100k + 200k to two distinct A beneficiaries; B delivers 150k.
        $this->benefit($aBens[0], $this->progA, $this->mdaA, 100_000, 'dutse');
        $this->benefit($aBens[1], $this->progA, $this->mdaA, 200_000, 'dutse');
        $this->benefit($bBens[0], $this->progB, $this->mdaB, 150_000, 'hadejia');

        // One A→B referral (created) and one grievance handled by A (open).
        Referral::create([
            'beneficiary_id' => $aBens[0]->id, 'from_mda_id' => $this->mdaA->id, 'to_mda_id' => $this->mdaB->id,
            'need' => 'Health service', 'status' => 'created',
        ]);
        Grievance::create([
            'handling_mda_id' => $this->mdaA->id, 'beneficiary_id' => $aBens[0]->id,
            'category' => 'payment', 'channel' => 'walk_in', 'description' => 'No payment.', 'status' => 'open',
        ]);

        // Import batch in A with three resolved rows (served / new / skipped).
        $this->importRows($this->mdaA, $this->progA, [
            ['exact', 'link'],     // matched + served
            ['probable', 'new'],   // matched + created new
            ['none', 'skip'],      // no match + skipped
        ]);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda?->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    private function benefit(Beneficiary $beneficiary, Programme $programme, Mda $mda, int $kobo, string $lga): void
    {
        Benefit::factory()->create([
            'beneficiary_id' => $beneficiary->id, 'programme_id' => $programme->id, 'mda_id' => $mda->id,
            'monetary_value' => $kobo, 'lga' => $lga, 'status' => 'verified',
        ]);
    }

    /**
     * @param  list<array{0: string, 1: string}>  $rows  [match_band, resolution]
     */
    private function importRows(Mda $mda, Programme $programme, array $rows): void
    {
        // Budget-neutral activity for the import batch (budgets are asserted via the
        // dedicated per-MDA activities created in setUp).
        $activity = Activity::factory()->forProgramme($programme, $mda)->create(['budget_amount' => 0]);
        $batchId = (string) Str::uuid();
        DB::table('import_batches')->insert([
            'id' => $batchId, 'owner_mda_id' => $mda->id, 'original_filename' => 'sample.csv',
            'stored_path' => 'imports/sample.csv', 'source' => 'csv', 'activity_id' => $activity->id, 'status' => 'completed',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        foreach ($rows as $i => [$band, $resolution]) {
            DB::table('import_rows')->insert([
                'id' => (string) Str::uuid(), 'import_batch_id' => $batchId, 'row_number' => $i + 1,
                'payload' => json_encode([]), 'is_valid' => true, 'match_band' => $band, 'resolution' => $resolution,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    /** @return array<string, mixed> */
    private function metricsFor(string $key): array
    {
        return app(DashboardService::class)->forUser($this->users[$key])['metrics'];
    }

    public function test_state_wide_scope_aggregates_across_all_mdas(): void
    {
        app(DashboardSnapshotService::class)->refreshAll();
        $m = $this->metricsFor('exec');

        // Registry: all 5 beneficiaries; by-status + by-lga split.
        $this->assertSame(5, $m['registry']['beneficiaries']['total']);
        $this->assertSame(4, $m['registry']['beneficiaries']['by_status']['active']);
        $this->assertSame(1, $m['registry']['beneficiaries']['by_status']['suspended']);
        $this->assertSame(3, $m['registry']['beneficiaries']['by_lga']['dutse']);

        // Programmes: both active, state-wide.
        $this->assertSame(2, $m['programmes']['active']);

        // Benefits: 3 deliveries totalling 450k; allocated = both budgets.
        $this->assertSame(3, $m['benefits']['disbursed']['benefit_count']);
        $this->assertSame(450_000, $m['benefits']['disbursed']['total_value']);
        $this->assertSame(1_500_000, $m['benefits']['budget']['allocated']);

        // Coordination metrics present state-wide.
        $this->assertSame(1, $m['referrals']['total']);
        $this->assertSame(1, $m['grievances']['total']);
        $this->assertSame(2, $m['duplicates']['matches_surfaced']);
        $this->assertSame(1, $m['duplicates']['resolved_served']);
        $this->assertSame(1, $m['duplicates']['resolved_skipped']);
    }

    public function test_mda_scope_only_sees_its_own_data(): void
    {
        app(DashboardSnapshotService::class)->refreshAll();

        $a = $this->metricsFor('officerA');
        $this->assertSame(3, $a['registry']['beneficiaries']['total']);
        $this->assertSame(1, $a['programmes']['active']); // only programme A
        $this->assertSame(2, $a['benefits']['disbursed']['benefit_count']);
        $this->assertSame(300_000, $a['benefits']['disbursed']['total_value']);
        $this->assertSame(1_000_000, $a['benefits']['budget']['allocated']);
        $this->assertSame(1, $a['grievances']['total']);   // A handles it
        $this->assertSame(1, $a['referrals']['total']);    // A is the from-MDA

        $b = $this->metricsFor('officerB');
        $this->assertSame(2, $b['registry']['beneficiaries']['total']);
        $this->assertSame(1, $b['benefits']['disbursed']['benefit_count']);
        $this->assertSame(150_000, $b['benefits']['disbursed']['total_value']);
        $this->assertSame(0, $b['grievances']['total']);   // none in B
        $this->assertSame(1, $b['referrals']['total']);    // B is the to-MDA
        $this->assertSame(0, $b['duplicates']['matches_surfaced']); // no imports in B
    }

    public function test_partner_scope_is_limited_to_funded_programmes(): void
    {
        app(DashboardSnapshotService::class)->refreshAll();
        $p = $this->metricsFor('partner');

        // Only programme A's deliveries (2 × ... = 300k) and its 2 served beneficiaries.
        $this->assertSame(2, $p['benefits']['disbursed']['benefit_count']);
        $this->assertSame(300_000, $p['benefits']['disbursed']['total_value']);
        $this->assertSame(1_000_000, $p['benefits']['budget']['allocated']); // programme A budget
        $this->assertSame(2, $p['registry']['beneficiaries']['total']);      // served by A

        // Coordination metrics do not apply to a partner scope.
        $this->assertNull($p['referrals']);
        $this->assertNull($p['grievances']);
        $this->assertNull($p['duplicates']);
        $this->assertNull($p['registry']['households']);
    }

    public function test_reads_come_from_the_summary_not_a_raw_scan(): void
    {
        $snapshots = app(DashboardSnapshotService::class);
        $snapshots->refreshAll();

        $before = $this->metricsFor('officerA')['benefits']['disbursed']['benefit_count'];
        $this->assertSame(2, $before);

        // A new delivery lands in the raw ledger…
        $this->benefit(
            Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id]),
            $this->progA, $this->mdaA, 50_000, 'dutse',
        );

        // …but the dashboard still shows the snapshot value (served from summary).
        $this->assertSame(2, $this->metricsFor('officerA')['benefits']['disbursed']['benefit_count']);

        // Only after a refresh does it reflect the new row.
        $snapshots->refreshAll();
        $this->assertSame(3, $this->metricsFor('officerA')['benefits']['disbursed']['benefit_count']);
    }

    public function test_endpoint_is_permission_gated_and_scope_aware(): void
    {
        app(DashboardSnapshotService::class)->refreshAll();

        $token = $this->users['officerA']->createToken('t')->plainTextToken;
        $this->withToken($token)->getJson('/api/v1/dashboard')
            ->assertOk()
            ->assertJsonPath('data.scope.kind', 'mda')
            ->assertJsonPath('data.metrics.registry.beneficiaries.total', 3);

        // A user with no role/permission is refused.
        $noRole = User::factory()->create(['mda_id' => $this->mdaA->id, 'role_id' => null]);
        $this->app['auth']->forgetGuards();
        $this->withToken($noRole->createToken('t')->plainTextToken)->getJson('/api/v1/dashboard')->assertStatus(403);
    }

    public function test_scopes_reconcile_to_the_state_wide_totals(): void
    {
        app(DashboardSnapshotService::class)->refreshAll();

        $all = $this->metricsFor('exec');
        $a = $this->metricsFor('officerA');
        $b = $this->metricsFor('officerB');

        // The two MDA scopes sum to the state-wide totals (FR-RPT-02 reconciliation).
        $this->assertSame(
            $all['registry']['beneficiaries']['total'],
            $a['registry']['beneficiaries']['total'] + $b['registry']['beneficiaries']['total'],
        );
        $this->assertSame(
            $all['benefits']['disbursed']['total_value'],
            $a['benefits']['disbursed']['total_value'] + $b['benefits']['disbursed']['total_value'],
        );
        $this->assertSame(
            $all['benefits']['disbursed']['benefit_count'],
            $a['benefits']['disbursed']['benefit_count'] + $b['benefits']['disbursed']['benefit_count'],
        );
        $this->assertSame(
            $all['programmes']['active'],
            $a['programmes']['active'] + $b['programmes']['active'],
        );
    }

    public function test_endpoint_denies_cross_scope_access(): void
    {
        app(DashboardSnapshotService::class)->refreshAll();

        // There is no scope parameter: each caller's endpoint returns ONLY their own
        // scope — an MDA user can never obtain another MDA's (or the state-wide) data.
        $this->withToken($this->users['officerA']->createToken('t')->plainTextToken)
            ->getJson('/api/v1/dashboard')
            ->assertOk()
            ->assertJsonPath('data.scope.kind', 'mda')
            ->assertJsonPath('data.metrics.registry.beneficiaries.total', 3);
        $this->app['auth']->forgetGuards();

        $this->withToken($this->users['officerB']->createToken('t')->plainTextToken)
            ->getJson('/api/v1/dashboard')
            ->assertOk()
            ->assertJsonPath('data.metrics.registry.beneficiaries.total', 2);
        $this->app['auth']->forgetGuards();

        // A partner sees only funded-programme figures, no coordination data.
        $this->withToken($this->users['partner']->createToken('t')->plainTextToken)
            ->getJson('/api/v1/dashboard')
            ->assertOk()
            ->assertJsonPath('data.scope.kind', 'partner')
            ->assertJsonPath('data.metrics.benefits.disbursed.benefit_count', 2)
            ->assertJsonPath('data.metrics.referrals', null);
    }
}
