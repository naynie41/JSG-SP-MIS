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
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Export\Charts\SvgChart;
use App\Domain\Reporting\Export\DashboardBoardExportBuilder;
use App\Domain\Reporting\Export\ReportColumn;
use App\Domain\Reporting\Export\ReportData;
use App\Domain\Reporting\Export\ReportFigure;
use App\Domain\Reporting\Services\DashboardService;
use App\Domain\Reporting\Services\DashboardSnapshotService;
use App\Domain\Reporting\Support\DashboardFilter;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Executive dashboard export (PRD FR-RPT-03): CSV/Excel/PDF of the current scoped +
 * filtered view. Gated by `reporting.export`; AGGREGATE-only — never raw PII.
 */
class DashboardExportTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private Programme $programme;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mda = Mda::factory()->create(['name' => 'MDA A']);
        $this->programme = Programme::factory()->individual()->create(['status' => 'active']);
        $activity = Activity::factory()->forProgramme($this->programme, $this->mda)->inLgaCode('dutse')->create(['status' => 'active', 'budget_amount' => 1_000_000, 'target_beneficiaries' => 4]);

        // A distinctively-named beneficiary so we can prove the export never leaks it.
        $ben = Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id, 'lga' => 'dutse', 'first_name' => 'Zzxq', 'last_name' => 'Secretname']);
        Benefit::factory()->create(['beneficiary_id' => $ben->id, 'programme_id' => $this->programme->id, 'mda_id' => $this->mda->id, 'activity_id' => $activity->id, 'lga' => 'dutse', 'monetary_value' => 250_000, 'status' => 'verified']);

        app(DashboardSnapshotService::class)->refreshAll();
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda?->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function download(User $user, string $query = ''): TestResponse
    {
        $token = $user->createToken('t')->plainTextToken;

        return $this->withToken($token)->get('/api/v1/dashboard/export'.$query);
    }

    public function test_executive_exports_aggregate_csv(): void
    {
        $response = $this->download($this->user(null, RoleKey::Executive), '?format=csv')->assertOk();

        $this->assertStringContainsString('text/csv', (string) $response->headers->get('content-type'));
        $body = $response->streamedContent();

        // Aggregate metric labels are present…
        $this->assertStringContainsString('Total beneficiaries', $body);
        $this->assertStringContainsString('Active programmes', $body);

        // …and NO raw beneficiary-level data leaks (never a name or id column).
        $this->assertStringNotContainsString('Secretname', $body);
        $this->assertStringNotContainsString('beneficiary_id', $body);
    }

    public function test_export_supports_excel_and_pdf(): void
    {
        $exec = $this->user(null, RoleKey::Executive);

        $xlsx = $this->download($exec, '?format=xlsx')->assertOk();
        $this->assertStringContainsString('spreadsheetml', (string) $xlsx->headers->get('content-type'));

        $pdf = $this->download($exec, '?format=pdf')->assertOk();
        $this->assertStringContainsString('application/pdf', (string) $pdf->headers->get('content-type'));
    }

    public function test_export_reflects_the_active_filter(): void
    {
        // Filtering to a programme with no deliveries → net-unique 0 in the file.
        $other = Programme::factory()->individual()->create(['status' => 'active']);
        Activity::factory()->forProgramme($other, $this->mda)->create(['status' => 'active']);

        $body = $this->download($this->user(null, RoleKey::Executive), '?format=csv&programme_id='.$other->id)->assertOk()->streamedContent();

        // The unfiltered export has "…,1" for net-unique; the filtered one has 0.
        $this->assertMatchesRegularExpression('/Total beneficiaries.*0/', $body);
    }

    public function test_export_requires_the_reporting_export_permission(): void
    {
        $noRole = User::factory()->create(['mda_id' => $this->mda->id, 'role_id' => null]);

        $this->download($noRole, '?format=csv')->assertStatus(403);
    }

    /* ----------------------------------------------------------- MDA dashboard */

    public function test_an_mda_exports_its_dashboard_as_a_branded_pdf(): void
    {
        $response = $this->download($this->user($this->mda, RoleKey::MdaAdmin), '?format=pdf')->assertOk();

        $this->assertStringContainsString('application/pdf', (string) $response->headers->get('content-type'));
        $this->assertStringContainsString('mda-dashboard-', (string) $response->headers->get('content-disposition'));

        $pdf = $response->streamedContent();
        $this->assertStringStartsWith('%PDF', $pdf);
        $this->assertStringContainsString('/Subtype /Image', $pdf, 'the crest is on the letterhead');
    }

    public function test_an_mda_dashboard_exports_only_as_pdf(): void
    {
        $officer = $this->user($this->mda, RoleKey::MdaAdmin);

        $this->download($officer, '?format=csv')->assertStatus(422)->assertJsonPath('error.code', 'PDF_ONLY');
        $this->download($officer, '?format=xlsx')->assertStatus(422);
        // With no format asked for, a PDF is what an MDA gets.
        $this->download($officer)->assertOk();
    }

    public function test_the_mda_pdf_reads_the_same_figures_as_the_dashboard(): void
    {
        $officer = $this->user($this->mda, RoleKey::MdaAdmin);
        $dashboard = app(DashboardService::class)->forUser($officer);

        $data = app(DashboardBoardExportBuilder::class)->build($dashboard, DashboardFilter::none(), 'MDA A');

        $this->assertSame('MDA dashboard', $data->title);
        $this->assertSame('MDA A', $data->scopeLabel);
        $this->assertTrue($data->crest);
        $this->assertSame('All periods · All programmes · All LGAs', $data->subtitle);

        $tiles = array_column($data->highlights, 'value', 'label');
        // The tile on screen reads registry.beneficiaries.total under this label.
        $this->assertSame(number_format($dashboard['metrics']['registry']['beneficiaries']['total']), $tiles['Total beneficiaries']);
        $this->assertArrayHasKey('Value delivered', $tiles);

        $this->assertSame([
            'New registrations by month', 'Value delivered by month',
            'Quality of your records', 'Women and men',
            'Age groups', 'Household size',
            'Coverage across your LGAs', 'Largest LGAs',
            'Benefits delivered', 'Records',
        ], array_map(static fn (ReportFigure $f): string => $f->title, $data->figures));

        $this->assertSame(['Programme', 'Reached', 'Target', 'Progress', 'Value delivered', 'Budget', 'Status'], array_map(
            static fn (ReportColumn $c): string => $c->label,
            $data->columns,
        ));
        $this->assertNotEmpty($data->rows);
    }

    public function test_every_chart_in_the_mda_pdf_is_a_drawn_image_with_its_values(): void
    {
        $officer = $this->user($this->mda, RoleKey::MdaAdmin);
        $data = app(DashboardBoardExportBuilder::class)->build(app(DashboardService::class)->forUser($officer), DashboardFilter::none());

        $gender = $this->figure($data, 'Women and men');
        $this->assertStringStartsWith('data:image/svg+xml;base64,', (string) $gender->image);
        $this->assertGreaterThan(0, $gender->imageWidth);

        $records = $this->figure($data, 'Records');
        $this->assertContains('Active', array_column($records->items, 'label'));

        // Charts are laid out two to a row in the order given.
        $this->assertSame([2, 2, 2, 2, 2], array_map('count', $data->figureRows()));
    }

    public function test_the_mda_pdf_draws_the_lga_map_shaded_by_band(): void
    {
        $officer = $this->user($this->mda, RoleKey::MdaAdmin);
        $square = static fn (float $lon, float $lat): array => ['type' => 'Polygon', 'coordinates' => [[[$lon, $lat], [$lon + 0.3, $lat], [$lon + 0.3, $lat + 0.3], [$lon, $lat + 0.3], [$lon, $lat]]]];
        $map = [
            'rows' => [['key' => 'dutse', 'band' => 'red'], ['key' => 'gumel', 'band' => 'green']],
            'boundaries' => [
                ['code' => 'dutse', 'name' => 'Dutse', 'geometry' => $square(9.3, 11.7)],
                ['code' => 'gumel', 'name' => 'Gumel', 'geometry' => $square(9.4, 12.6)],
                ['code' => 'auyo', 'name' => 'Auyo', 'geometry' => $square(9.9, 12.3)],
            ],
        ];

        $data = app(DashboardBoardExportBuilder::class)->build(app(DashboardService::class)->forUser($officer), DashboardFilter::none(), null, $map);

        $figure = $this->figure($data, 'Coverage across your LGAs');
        $svg = base64_decode(substr((string) $figure->image, strlen('data:image/svg+xml;base64,')));
        $this->assertSame(3, substr_count($svg, '<path'));
        $this->assertStringContainsString('#B23A31', $svg); // Dutse, low
        $this->assertStringContainsString('#2F7D3B', $svg); // Gumel, high
        $this->assertStringContainsString('#C9CBC1', $svg); // Auyo, no coverage
        $this->assertSame('1 LGA', array_column($figure->items, 'value')[0]);
    }

    public function test_without_boundaries_the_map_card_says_so(): void
    {
        $officer = $this->user($this->mda, RoleKey::MdaAdmin);
        $data = app(DashboardBoardExportBuilder::class)->build(app(DashboardService::class)->forUser($officer), DashboardFilter::none());

        $figure = $this->figure($data, 'Coverage across your LGAs');
        $this->assertNull($figure->image);
        $this->assertStringContainsString('boundary map is not loaded', (string) $figure->note);
    }

    public function test_chart_text_is_escaped_inside_the_svg(): void
    {
        $chart = SvgChart::bars([['label' => 'Food & <Shelter>', 'count' => 3]], 330);

        $svg = base64_decode(substr((string) $chart['uri'], strlen('data:image/svg+xml;base64,')));
        $this->assertStringContainsString('Food &amp; &lt;Shelter&gt;', $svg);
    }

    public function test_the_mda_pdf_states_the_filters_it_was_exported_with(): void
    {
        $officer = $this->user($this->mda, RoleKey::MdaAdmin);
        $filter = new DashboardFilter(year: 2026, quarter: 3, programmeId: $this->programme->id, lga: 'dutse');

        $data = app(DashboardBoardExportBuilder::class)->build(app(DashboardService::class)->forUser($officer, $filter), $filter);

        $this->assertSame("Q3 2026 · {$this->programme->name} · Dutse", $data->subtitle);
    }

    public function test_the_mda_pdf_never_carries_a_beneficiarys_identity(): void
    {
        $officer = $this->user($this->mda, RoleKey::MdaAdmin);
        $data = app(DashboardBoardExportBuilder::class)->build(app(DashboardService::class)->forUser($officer), DashboardFilter::none());

        $html = View::make('reports.pdf', ['data' => $data])->render();
        $this->assertStringNotContainsString('Secretname', $html);
        $this->assertStringNotContainsString('Zzxq', $html);
    }

    /* ------------------------------------------- the administration console's board */

    public function test_the_admin_console_exports_the_whole_board_not_the_executive_sections(): void
    {
        $admin = $this->user(null, RoleKey::SystemAdministrator);

        $response = $this->download($admin, '?format=pdf&view=board')->assertOk();
        $this->assertSame('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('state-dashboard-', (string) $response->headers->get('Content-Disposition'));

        // Same endpoint WITHOUT the board marker is still the executive suite's
        // sectioned export — that page must keep its CSV and Excel.
        $this->download($admin, '?format=csv')->assertOk();
        $this->download($admin, '?format=xlsx')->assertOk();
    }

    public function test_the_state_wide_board_is_pdf_only(): void
    {
        $admin = $this->user(null, RoleKey::SystemAdministrator);

        $this->download($admin, '?format=csv&view=board')->assertStatus(422)->assertJsonPath('error.code', 'PDF_ONLY');
        $this->download($admin, '?format=xlsx&view=board')->assertStatus(422);
    }

    public function test_the_state_wide_pdf_carries_every_chart_the_board_shows(): void
    {
        $admin = $this->user(null, RoleKey::SystemAdministrator);
        $data = app(DashboardBoardExportBuilder::class)->build(
            app(DashboardService::class)->forUser($admin),
            DashboardFilter::none(),
            null,
            null,
            true,
        );

        $this->assertSame('State-wide dashboard', $data->title);
        $this->assertTrue($data->crest);

        // The same cards as the MDA board, in the same order, plus the cross-agency
        // comparison after the trend/quality pair.
        $this->assertSame([
            'New registrations by month', 'Value delivered by month',
            'Quality of your records', 'Delivery by agency',
            'Women and men', 'Age groups',
            'Household size', 'Coverage across your LGAs',
            'Largest LGAs', 'Benefits delivered', 'Records',
        ], array_map(static fn (ReportFigure $f): string => $f->title, $data->figures));

        $byMda = $this->figure($data, 'Delivery by agency');
        $this->assertStringStartsWith('data:image/svg+xml;base64,', (string) $byMda->image);
        // It takes the full width: agency names are the first thing to become
        // unreadable in a half-width card.
        $this->assertTrue($byMda->wide);
        // Each agency's own budget share rides with its value, as on screen.
        $this->assertStringContainsString('MDA A', $byMda->items[0]['label']);
        $this->assertStringContainsString('of its budget', $byMda->items[0]['value']);
        $this->assertStringContainsString('add up to more than the state total', (string) $byMda->note);
    }

    public function test_an_mda_board_never_carries_the_cross_agency_comparison(): void
    {
        $officer = $this->user($this->mda, RoleKey::MdaAdmin);
        $data = app(DashboardBoardExportBuilder::class)->build(
            app(DashboardService::class)->forUser($officer),
            DashboardFilter::none(),
        );

        $titles = array_map(static fn (ReportFigure $f): string => $f->title, $data->figures);
        $this->assertNotContains('Delivery by agency', $titles);
    }

    private function figure(ReportData $data, string $title): ReportFigure
    {
        foreach ($data->figures as $figure) {
            if ($figure->title === $title) {
                return $figure;
            }
        }

        $this->fail("No “{$title}” figure");
    }
}
