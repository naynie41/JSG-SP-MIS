<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Export\MonthlyProjectReportBuilder;
use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Models\ReportRun;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use App\Domain\Reporting\Services\MonthlyProjectService;
use App\Domain\Reporting\Services\ReportService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * "Monthly project report" (FR-RPT-12b) — how an MDA's activities did in one month.
 *
 * The load-bearing property is the separation of FLOW from CUMULATIVE. A month's
 * delivery divided by a lifetime target is a meaningless ratio that would paint every
 * healthy project red, and the first test here exists purely to stop someone
 * "simplifying" the two measures back into one.
 */
class MonthlyProjectReportTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private Programme $programme;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->programme = Programme::factory()->individual()->create(['name' => 'Cash Transfer']);
    }

    /**
     * Several tests here freeze the clock to check which month is "last complete".
     *
     * Clearing it in tearDown rather than at the end of each test is the point: an
     * in-test reset is skipped when an assertion fails, and a frozen clock then leaks
     * into every class that runs afterwards — where it surfaces as an unrelated date
     * test failing for no visible reason. The base TestCase does not reset it.
     */
    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function admin(): User
    {
        return User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
    }

    private function activity(array $attributes = [], ?Mda $mda = null): Activity
    {
        return Activity::factory()
            ->forProgramme($this->programme, $mda ?? $this->mda)
            ->create($attributes + [
                'status' => ActivityStatus::Active,
                'involves_beneficiaries' => true,
                'target_beneficiaries' => 100,
                'budget_amount' => 1_000_000,
                'starts_on' => '2026-01-01',
                'ends_on' => '2026-12-31',
            ]);
    }

    /** One delivery to a fresh person, on a given date, under a given activity. */
    private function deliver(Activity $activity, string $date, int $kobo = 100_000, ?Mda $mda = null): void
    {
        Benefit::factory()->create([
            'beneficiary_id' => Beneficiary::factory()->create(['owner_mda_id' => ($mda ?? $this->mda)->id])->id,
            'programme_id' => $activity->programme_id,
            'activity_id' => $activity->id,
            'mda_id' => ($mda ?? $this->mda)->id,
            'monetary_value' => $kobo,
            'delivery_date' => $date,
            'status' => 'verified',
        ]);
    }

    private function build(User $user, int $year, int $month): array
    {
        $scope = app(DashboardScopeResolver::class)->forUser($user);

        return app(MonthlyProjectService::class)->build($scope, $year, $month);
    }

    /* ------------------------------------------------- flow vs cumulative */

    public function test_a_project_on_track_overall_is_not_marked_red_by_a_quiet_month(): void
    {
        // 90 of 100 people reached across the year — comfortably green — but only one
        // of them in the month under report. Divide the month by the lifetime target
        // and you get 1%, which is the bug this test exists to prevent.
        $activity = $this->activity();
        for ($i = 0; $i < 89; $i++) {
            $this->deliver($activity, '2026-03-15');
        }
        $this->deliver($activity, '2026-08-10');

        $data = $this->build($this->admin(), 2026, 8);
        $project = $data['projects'][0];

        $this->assertSame(1, $project['month']['reached'], 'the month figure counts only August');
        $this->assertSame(90, $project['to_date']['reached'], 'the cumulative figure counts everything');
        $this->assertSame(0.9, $project['completion_rate'], 'completion is cumulative over target');
        $this->assertSame('green', $project['traffic_light'], 'a quiet month must not turn a healthy project red');
    }

    public function test_the_month_figure_excludes_other_months(): void
    {
        $activity = $this->activity();
        $this->deliver($activity, '2026-07-31', 500_000);
        $this->deliver($activity, '2026-08-01', 100_000);
        $this->deliver($activity, '2026-08-31', 200_000);
        $this->deliver($activity, '2026-09-01', 900_000);

        $data = $this->build($this->admin(), 2026, 8);

        // Both boundary days belong to August; neither neighbour leaks in.
        $this->assertSame(300_000, $data['totals']['value']);
        $this->assertSame(2, $data['totals']['reached']);
        // And last month is picked up for the comparison.
        $this->assertSame(500_000, $data['previous_totals']['value']);
    }

    /* ------------------------------------------------------------- scope */

    public function test_another_mdas_activity_never_appears(): void
    {
        $other = Mda::factory()->create(['name' => 'Ministry of Education']);
        $mine = $this->activity(['name' => 'Mine']);
        $theirs = $this->activity(['name' => 'Theirs'], $other);

        $this->deliver($mine, '2026-08-05');
        $this->deliver($theirs, '2026-08-05', 100_000, $other);

        $data = $this->build($this->admin(), 2026, 8);
        $names = array_column($data['projects'], 'name');

        $this->assertContains('Mine', $names);
        $this->assertNotContains('Theirs', $names);
    }

    /* --------------------------------------------- activities without people */

    public function test_a_non_beneficiary_activity_reports_without_coverage_figures(): void
    {
        $this->activity([
            'name' => 'Build a clinic',
            'involves_beneficiaries' => false,
            'target_beneficiaries' => null,
        ]);

        $project = $this->build($this->admin(), 2026, 8)['projects'][0];

        // Present, with its budget and timeline — but never scored on reach it was
        // never meant to have. A zero here would read as failure.
        $this->assertSame('Build a clinic', $project['name']);
        $this->assertFalse($project['tracks_people']);
        $this->assertNull($project['month']['reached']);
        $this->assertNull($project['target']);
        $this->assertNull($project['traffic_light']);
        $this->assertSame(1_000_000, $project['budget']['allocated']);
    }

    /* ---------------------------------------------------------- attention */

    public function test_an_activity_past_its_timeline_but_still_open_is_flagged(): void
    {
        $this->activity(['name' => 'Overran', 'ends_on' => '2026-06-30']);

        $data = $this->build($this->admin(), 2026, 8);
        $reasons = implode(' ', array_column($data['attention'], 'reason'));

        $this->assertStringContainsString('Timeline ended', $reasons);
    }

    public function test_a_silent_month_is_flagged(): void
    {
        $this->activity(['name' => 'Silent']);

        $data = $this->build($this->admin(), 2026, 8);
        $reasons = implode(' ', array_column($data['attention'], 'reason'));

        $this->assertStringContainsString('No delivery recorded this month', $reasons);
    }

    public function test_drafts_are_left_out(): void
    {
        $this->activity(['name' => 'Not started', 'status' => ActivityStatus::Draft]);

        $this->assertSame([], $this->build($this->admin(), 2026, 8)['projects']);
    }

    /* ------------------------------------------------------- default month */

    public function test_the_default_month_is_the_last_complete_one_never_the_current(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-09-03'));

        // The 3rd of September must not report September: three days against a full
        // August would show every project collapsing.
        $this->assertSame([2026, 8], MonthlyProjectService::lastCompleteMonth());

        Carbon::setTestNow(Carbon::parse('2026-01-15'));
        $this->assertSame([2025, 12], MonthlyProjectService::lastCompleteMonth(), 'January rolls back a year');

        Carbon::setTestNow();
    }

    /* ------------------------------------------------------------- the PDF */

    public function test_the_report_renders_as_a_pdf_with_the_two_measures_side_by_side(): void
    {
        $activity = $this->activity(['name' => 'Cash to households']);
        $this->deliver($activity, '2026-08-12');

        $scope = app(DashboardScopeResolver::class)->forUser($this->admin());
        $data = app(MonthlyProjectReportBuilder::class)->build($scope, 2026, 8);

        $this->assertSame('monthly_project', $data->reportKey);
        $this->assertTrue($data->crest);
        $this->assertStringContainsString('August 2026', $data->subtitle);

        $headings = array_map(fn ($c) => $c->label, $data->columns);
        $this->assertContains('Reached this month', $headings);
        $this->assertContains('Reached to date', $headings);

        $this->assertSame('Cash to households', $data->rows[0]['name']);
    }

    /* ------------------------------------------------------------- charts */

    public function test_no_figure_ever_silently_disappears_when_there_is_nothing_to_plot(): void
    {
        // THE REGRESSION THIS FILE EXISTS FOR. The first version returned null from a
        // figure when its data was empty, and the card vanished — so an empty scope and
        // a broken builder produced an identical page. Every figure must now render and
        // say why it is empty.
        $this->activity(['name' => 'Nothing delivered yet']);

        $scope = app(DashboardScopeResolver::class)->forUser($this->admin());
        $data = app(MonthlyProjectReportBuilder::class)->build($scope, 2026, 8);

        $this->assertCount(4, $data->figures, 'all four figures render regardless of data');

        foreach ($data->figures as $figure) {
            $this->assertNotSame('', $figure->title);
            if ($figure->image === null) {
                $this->assertNotNull($figure->note, "«{$figure->title}» drew nothing and must explain why");
            }
        }
    }

    public function test_the_month_on_month_comparison_survives_a_month_with_no_reach(): void
    {
        // The exact failure reported: value moved but nobody was counted as reached, so
        // the old two-bar chart of REACH drew nothing and the comparison disappeared.
        // It now lives in a tile, which always renders.
        $activity = $this->activity();
        $this->deliver($activity, '2026-07-10', 400_000);
        $this->deliver($activity, '2026-08-10', 600_000);

        $scope = app(DashboardScopeResolver::class)->forUser($this->admin());
        $data = app(MonthlyProjectReportBuilder::class)->build($scope, 2026, 8);

        $labels = array_column($data->highlights, 'label');
        $this->assertContains('People reached', $labels);
        $this->assertContains('Value delivered', $labels);

        $value = collect($data->highlights)->firstWhere('label', 'Value delivered');
        $this->assertStringContainsString('6,000.00', (string) $value['value']);
        // 400k -> 600k is +50%, and the note names the month it is measured against.
        $this->assertStringContainsString('+50% on July 2026', (string) $value['note']);
    }

    public function test_a_rise_from_nothing_is_not_reported_as_a_percentage(): void
    {
        // Dividing by a zero baseline is where percentage deltas go wrong; it is said
        // in words instead.
        $activity = $this->activity();
        $this->deliver($activity, '2026-08-10', 500_000);

        $scope = app(DashboardScopeResolver::class)->forUser($this->admin());
        $data = app(MonthlyProjectReportBuilder::class)->build($scope, 2026, 8);

        $value = collect($data->highlights)->firstWhere('label', 'Value delivered');
        $this->assertStringNotContainsString('%', (string) $value['note']);
        $this->assertStringContainsString('July 2026', (string) $value['note']);
    }

    public function test_the_trend_ends_on_the_month_reported_not_today(): void
    {
        // A report about August, generated later, must not draw September onward.
        Carbon::setTestNow(Carbon::parse('2026-12-15'));

        $activity = $this->activity(['starts_on' => '2025-01-01']);
        $this->deliver($activity, '2025-09-15', 250_000);  // far edge of the window
        $this->deliver($activity, '2026-08-10', 100_000);  // the reported month
        $this->deliver($activity, '2026-11-10', 900_000);  // after the reported month

        $scope = app(DashboardScopeResolver::class)->forUser($this->admin());
        $d = app(MonthlyProjectService::class)->build($scope, 2026, 8);

        $byMonth = array_column($d['trend'], 'value', 'month');

        $this->assertCount(12, $byMonth);
        $this->assertArrayHasKey('2025-09', $byMonth, 'the window runs twelve months back from the reported month');
        $this->assertArrayNotHasKey('2026-11', $byMonth, 'and stops at it');

        // These two are what the `endingAt` window actually buys, and the labels alone
        // would not catch either: the service builds the month keys itself, so they
        // read correctly even when the QUERY behind them is anchored on today.
        //   - the old September 2025 delivery sits before a now-anchored `since` and
        //     would have been queried away, leaving a zero;
        //   - the November delivery sits after the reported month and would have been
        //     counted into it.
        $this->assertSame(250_000, $byMonth['2025-09'], 'data at the far edge of the window survives');
        $this->assertSame(100_000, $byMonth['2026-08']);
        $this->assertSame(350_000, array_sum($byMonth), 'and nothing from outside the window leaks in');
    }

    public function test_only_projects_with_a_target_are_rated_in_the_standing(): void
    {
        $this->activity(['name' => 'Rated', 'target_beneficiaries' => 100]);
        $this->activity(['name' => 'No target', 'target_beneficiaries' => null]);
        $this->activity(['name' => 'Not people', 'involves_beneficiaries' => false, 'target_beneficiaries' => null]);

        $scope = app(DashboardScopeResolver::class)->forUser($this->admin());
        $d = app(MonthlyProjectService::class)->build($scope, 2026, 8);

        // Three activities, one standing: "unrated" is not a standing and must not be
        // folded into a slice.
        $this->assertSame(3, $d['totals']['activities']);
        $this->assertSame(1, array_sum($d['standing']));
    }

    /* ----------------------------------------------------------- endpoint */

    public function test_the_endpoint_queues_a_run_for_the_callers_own_scope(): void
    {
        $response = $this->actingAs($this->admin())
            ->postJson('/api/v1/reports/monthly-project', ['year' => 2026, 'month' => 8]);

        $response->assertStatus(202);

        $run = ReportRun::query()->firstOrFail();
        $this->assertSame(ReportRun::KEY_MONTHLY_PROJECT, $run->report_key);
        $this->assertSame(['year' => 2026, 'month' => 8], $run->params);
        $this->assertSame('pdf', $run->format);
    }

    public function test_the_queued_run_produces_a_real_pdf_end_to_end(): void
    {
        // The builder tests above check the DATA. This checks the thing the officer
        // actually receives: that it renders through the queue and lands as a PDF, not
        // a failed run with a stack trace in it.
        Storage::fake('local');

        $activity = $this->activity(['name' => 'Cash to households']);
        $this->deliver($activity, '2026-08-12');

        $id = $this->actingAs($this->admin())
            ->postJson('/api/v1/reports/monthly-project', ['year' => 2026, 'month' => 8])
            ->assertStatus(202)
            ->json('data.id');

        $run = ReportRun::query()->findOrFail($id);
        $this->assertSame(ReportRun::STATUS_READY, $run->status, (string) $run->error);
        $this->assertNotNull($run->file_path);

        $bytes = Storage::disk('local')->get($run->file_path);
        $this->assertStringStartsWith('%PDF', (string) $bytes, 'the stored file must actually be a PDF');
        $this->assertStringEndsWith('.pdf', (string) $run->file_name);
    }

    public function test_it_is_a_pdf_even_when_something_asks_for_a_spreadsheet(): void
    {
        // It is in the catalogue so it can be SCHEDULED, and the schedule form offers a
        // format. A CSV of this report would carry the table and silently drop the
        // charts, the month-on-month comparison and the attention list — so the format
        // is pinned rather than refused.
        $run = app(ReportService::class)->request(
            $this->admin(),
            'monthly_project',
            ReportFormat::Csv,
        );

        $this->assertSame('pdf', $run->format);
    }

    public function test_the_endpoint_refuses_a_month_that_has_not_finished(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-09-15'));

        $response = $this->actingAs($this->admin())
            ->postJson('/api/v1/reports/monthly-project', ['year' => 2026, 'month' => 9]);

        $response->assertStatus(422);
        $this->assertStringContainsString(
            'has not finished yet',
            json_encode($response->json('error.details') ?? []) ?: '',
        );

        Carbon::setTestNow();
    }

    public function test_the_endpoint_defaults_to_the_last_complete_month(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-09-15'));

        $this->actingAs($this->admin())
            ->postJson('/api/v1/reports/monthly-project', [])
            ->assertStatus(202);

        $this->assertSame(['year' => 2026, 'month' => 8], ReportRun::query()->firstOrFail()->params);

        Carbon::setTestNow();
    }
}
