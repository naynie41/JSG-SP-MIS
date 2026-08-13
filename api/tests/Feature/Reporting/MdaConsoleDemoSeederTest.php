<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Matching\Enums\MatchBand;
use App\Domain\Programme\Models\Activity;
use App\Domain\Registry\Enums\ImportRowResolution;
use App\Domain\Registry\Models\ImportRow;
use App\Domain\Registry\Models\ServiceRequest;
use Database\Seeders\MdaConsoleDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The MDA-console demo seeder: every one of the six modules must render something real
 * for BOTH of the MDA's users, and none of it may be invalid domain data.
 *
 * This is the check that the console is demonstrable end to end. A module that renders
 * empty in a fresh stack looks broken, and a seeder that writes a band or resolution the
 * enums do not define produces screens full of unknown labels.
 */
class MdaConsoleDemoSeederTest extends TestCase
{
    use RefreshDatabase;

    private Mda $home;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Called directly: $this->seed() trips Laravel's interactive production guard.
        $seeder = new MdaConsoleDemoSeeder;
        $seeder->setContainer(app());
        $seeder->run();

        $this->home = Mda::query()->withoutGlobalScopes()->where('name', 'Ministry of Health')->firstOrFail();

        // TWO DISTINCT users on the single MDA role (FR-UAM-01). Coordination flows need
        // two actors inside one MDA — a requester and a decider — and the console must
        // render for any of them, so the demo is checked against both.
        $mdaUsers = User::query()->withoutGlobalScope(MdaScope::class)
            ->where('mda_id', $this->home->id)
            ->whereHas('role', fn ($q) => $q->where('key', RoleKey::MdaAdmin->value))
            ->orderBy('email')
            ->take(2)
            ->get();

        $this->assertCount(2, $mdaUsers, 'the demo seeder must create two MDA users in the home MDA');

        $this->users['first'] = $mdaUsers[0];
        $this->users['second'] = $mdaUsers[1];
    }

    private function send(string $key, string $method, string $url): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /** Both MDA users, so no module is demonstrable for only one of them. */
    /** @return list<string> */
    private function roles(): array
    {
        return ['first', 'second'];
    }

    /* --------------------------------------------------------------- the actors */

    public function test_it_seeds_two_distinct_mda_users_on_the_single_mda_role(): void
    {
        $this->assertSame($this->home->id, $this->users['first']->mda_id);
        $this->assertSame($this->home->id, $this->users['second']->mda_id);
        $this->assertNotSame($this->users['first']->id, $this->users['second']->id);

        // One MDA role, and it can do the whole job — approvals and export included.
        foreach (['first', 'second'] as $who) {
            $this->assertSame(RoleKey::MdaAdmin->value, $this->users[$who]->role->key);
            $this->assertTrue($this->users[$who]->hasPermission('beneficiary.approve'));
            $this->assertTrue($this->users[$who]->hasPermission('beneficiary.export'));
            // …but never user administration, which is System-Administrator-only.
            $this->assertFalse($this->users[$who]->hasPermission('user.create'));
        }
    }

    /* ------------------------------------------------- module 1: Overview */

    public function test_the_overview_renders_with_non_zero_action_required_counters(): void
    {
        foreach ($this->roles() as $who) {
            $counters = $this->send($who, 'GET', '/api/v1/mda/action-required')->assertOk()->json('data');

            // A demo with an empty work queue cannot show what the console is for.
            $this->assertGreaterThan(0, $counters['pending_referrals'], 'an inbound referral must be waiting');
            $this->assertGreaterThan(0, $counters['pending_service_requests'], 'an approval must be waiting');
        }
    }

    public function test_the_dashboard_renders_for_both_roles(): void
    {
        foreach ($this->roles() as $who) {
            $metrics = $this->send($who, 'GET', '/api/v1/dashboard')->assertOk()->json('data.metrics');
            $this->assertGreaterThan(0, $metrics['registry']['beneficiaries']['total']);
        }
    }

    /* ------------------------------------------------- module 2: Programmes */

    public function test_the_mda_participates_in_programmes_with_both_activity_kinds(): void
    {
        foreach ($this->roles() as $who) {
            $programmes = $this->send($who, 'GET', '/api/v1/programmes?filter[participating]=1')->assertOk()->json('data');
            $this->assertNotEmpty($programmes, 'the MDA must participate in at least one programme');
        }

        $activities = Activity::query()->withoutGlobalScope(MdaScope::class)
            ->where('owner_mda_id', $this->home->id)->get();

        // The conditional wizard branches on this flag, so a demo needs both sides of it.
        $this->assertTrue($activities->contains(fn (Activity $a): bool => $a->involves_beneficiaries));
        $this->assertTrue(
            $activities->contains(fn (Activity $a): bool => ! $a->involves_beneficiaries),
            'an activity that registers NO beneficiaries must exist, or the Import Center picker filters nothing',
        );
    }

    /* ---------------------------------------------- module 3: Beneficiaries */

    public function test_the_registry_and_import_history_render(): void
    {
        foreach ($this->roles() as $who) {
            $this->assertNotEmpty(
                $this->send($who, 'GET', '/api/v1/beneficiaries')->assertOk()->json('data'),
                'the registry must not be empty',
            );
            $this->assertNotEmpty(
                $this->send($who, 'GET', '/api/v1/beneficiaries/imports')->assertOk()->json('data'),
                'the Import Center needs history to show',
            );
        }
    }

    public function test_seeded_identifiers_are_never_real_looking_pii(): void
    {
        // Synthetic only. The staged payloads carry names and an LGA — never an
        // identifier — so a demo stack can never leak a plausible NIN/BVN.
        //
        // Asserted on the payload KEYS, not on the serialised blob: a substring search
        // for "nin" also matches the LGA "birnin_kudu", which made this pass or fail
        // depending on which LGA the factory happened to pick.
        $payloads = ImportRow::query()->pluck('payload')->all();

        foreach ($payloads as $payload) {
            foreach (['nin', 'bvn'] as $identifier) {
                $this->assertArrayNotHasKey($identifier, (array) $payload, "a staged row must not carry {$identifier}");
            }
        }

        $this->assertDoesNotMatchRegularExpression(
            '/\b\d{11}\b/',
            (string) json_encode($payloads),
            'no 11-digit identifier may appear',
        );
    }

    /* -------------------------------------------- module 4: Service Delivery */

    public function test_service_delivery_has_benefits_referrals_and_requests_both_ways(): void
    {
        foreach ($this->roles() as $who) {
            $this->assertNotEmpty(
                $this->send($who, 'GET', '/api/v1/benefits')->assertOk()->json('data'),
                'delivered benefits must exist',
            );

            $incoming = $this->send($who, 'GET', '/api/v1/referrals?filter[direction]=incoming')->assertOk()->json('data');
            $outgoing = $this->send($who, 'GET', '/api/v1/referrals?filter[direction]=outgoing')->assertOk()->json('data');
            $this->assertNotEmpty($incoming, 'a received referral must exist');
            $this->assertNotEmpty($outgoing, 'a sent referral must exist');

            $inbox = $this->send($who, 'GET', '/api/v1/service-requests/inbox')->assertOk()->json('data.service_requests');
            $outbox = $this->send($who, 'GET', '/api/v1/service-requests/outbox')->assertOk()->json('data.service_requests');
            $this->assertNotEmpty($inbox, 'an incoming request-to-serve must exist');
            $this->assertNotEmpty($outbox, 'an outgoing request-to-serve must exist');
        }
    }

    public function test_the_approval_queue_reconciles_with_the_overview_counter(): void
    {
        $counter = $this->send('first', 'GET', '/api/v1/mda/action-required')->assertOk()
            ->json('data.pending_service_requests');

        $pending = collect($this->send('first', 'GET', '/api/v1/service-requests/inbox')->assertOk()->json('data.service_requests'))
            ->where('status', 'pending')
            ->count();

        $this->assertSame($pending, $counter, 'the counter and the queue must agree');
    }

    /* ------------------------------------- module 5: Duplicate Resolution */

    public function test_a_duplicate_case_exists_with_exact_and_probable_rows(): void
    {
        $rows = ImportRow::query()
            ->whereHas('batch', fn ($q) => $q->withoutGlobalScope(MdaScope::class)->where('owner_mda_id', $this->home->id))
            ->get();

        $bands = $rows->pluck('match_band')->unique()->filter()->values()->all();
        $this->assertContains(MatchBand::Exact->value, $bands, 'an exact match is needed to show a definitive duplicate');
        $this->assertContains(MatchBand::Probable->value, $bands, 'a probable match is needed to show adjudication');

        // Undecided rows, so Pending Reviews is actionable…
        $this->assertTrue($rows->contains(fn (ImportRow $r): bool => $r->match_band !== 'none' && $r->resolution === null));
        // …and decided ones, so Decisions and History are not empty.
        $this->assertTrue($rows->contains(fn (ImportRow $r): bool => $r->resolution !== null));
    }

    public function test_every_seeded_band_and_resolution_is_a_real_domain_value(): void
    {
        // These columns are plain strings, so an invented value persists silently and
        // then renders as an unknown label in the console.
        $bands = array_values(array_map('strval', array_filter(ImportRow::query()->pluck('match_band')->all())));
        $resolutions = array_values(array_map('strval', array_filter(ImportRow::query()->pluck('resolution')->all())));

        $validBands = array_column(MatchBand::cases(), 'value');
        $validResolutions = array_column(ImportRowResolution::cases(), 'value');

        foreach (array_unique($bands) as $band) {
            $this->assertContains($band, $validBands, "'{$band}' is not a MatchBand");
        }
        foreach (array_unique($resolutions) as $resolution) {
            $this->assertContains($resolution, $validResolutions, "'{$resolution}' is not an ImportRowResolution");
        }
    }

    public function test_a_flagged_row_carries_a_resolvable_match_candidate(): void
    {
        $row = ImportRow::query()
            ->where('match_band', MatchBand::Exact->value)
            ->whereHas('batch', fn ($q) => $q->withoutGlobalScope(MdaScope::class)->where('owner_mda_id', $this->home->id))
            ->firstOrFail();

        $candidate = ($row->match_candidates ?? [])[0] ?? null;
        $this->assertNotNull($candidate, 'a flagged row needs a candidate or the evidence panel is blank');
        $this->assertSame('registry', $candidate['type']);
        // The reference must resolve to a real record — that is what the reveal is built
        // from on the adjudication screen.
        $this->assertDatabaseHas('beneficiaries', ['id' => $candidate['reference']]);
    }

    /* ------------------------------------------------- module 6: Reports */

    public function test_reports_render_for_both_roles_and_obey_the_export_matrix(): void
    {
        foreach ($this->roles() as $who) {
            $datasets = array_column(
                $this->send($who, 'GET', '/api/v1/reports/adhoc/datasets')->assertOk()->json('data.datasets'),
                'key',
            );
            foreach (['benefits', 'beneficiaries', 'activities', 'referrals', 'duplicates'] as $dataset) {
                $this->assertContains($dataset, $datasets);
            }
        }

        // The matrix after the merge (FR-UAM-01): every MDA user may export, and the
        // export is bounded by MDA scope rather than by role.
        foreach ($this->roles() as $who) {
            $this->send($who, 'GET', '/api/v1/beneficiaries/export?format=csv')->assertSuccessful();
        }
    }

    /* --------------------------------------------------------------- hygiene */

    public function test_it_is_idempotent(): void
    {
        $before = [
            'rows' => ImportRow::query()->count(),
            'requests' => ServiceRequest::query()->count(),
            'activities' => Activity::query()->withoutGlobalScope(MdaScope::class)->count(),
        ];

        $seeder = new MdaConsoleDemoSeeder;
        $seeder->setContainer(app());
        $seeder->run();

        $this->assertSame($before['rows'], ImportRow::query()->count());
        $this->assertSame($before['requests'], ServiceRequest::query()->count());
        $this->assertSame($before['activities'], Activity::query()->withoutGlobalScope(MdaScope::class)->count());
    }

    public function test_it_refuses_to_run_in_production(): void
    {
        app()['env'] = 'production';
        $countBefore = ImportRow::query()->count();

        $seeder = new MdaConsoleDemoSeeder;
        $seeder->setContainer(app());
        $seeder->run();

        $this->assertSame($countBefore, ImportRow::query()->count(), 'demo data must never be seeded in production');
    }
}
