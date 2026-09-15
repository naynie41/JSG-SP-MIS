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
use App\Domain\Reporting\Export\MdaDashboardExportBuilder;
use App\Domain\Reporting\Export\ReportColumn;
use App\Domain\Reporting\Export\ReportData;
use App\Domain\Reporting\Export\ReportSummarySection;
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
        $this->assertStringContainsString('Net-unique beneficiaries', $body);
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
        $this->assertMatchesRegularExpression('/Net-unique beneficiaries.*0/', $body);
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

        $data = app(MdaDashboardExportBuilder::class)->build($dashboard, DashboardFilter::none());

        $this->assertSame('MDA dashboard', $data->title);
        $this->assertTrue($data->crest);
        $this->assertSame('All periods · All programmes · All LGAs', $data->subtitle);

        $glance = $this->section($data, 'At a glance');
        // The tile on screen reads registry.beneficiaries.total under this label.
        $this->assertSame(
            number_format($dashboard['metrics']['registry']['beneficiaries']['total']),
            $glance['Net-unique beneficiaries'],
        );
        $this->assertArrayHasKey('Value delivered', $glance);

        $titles = array_map(static fn (ReportSummarySection $s): string => $s->title, $data->summary);
        foreach (['Women and men', 'Age groups', 'Status of records', 'Largest LGAs'] as $expected) {
            $this->assertContains($expected, $titles);
        }

        $this->assertSame(['Programme', 'Reached', 'Target', 'Progress', 'Value delivered', 'Budget', 'Status'], array_map(
            static fn (ReportColumn $c): string => $c->label,
            $data->columns,
        ));
        $this->assertNotEmpty($data->rows);
    }

    public function test_the_mda_pdf_states_the_filters_it_was_exported_with(): void
    {
        $officer = $this->user($this->mda, RoleKey::MdaAdmin);
        $filter = new DashboardFilter(year: 2026, quarter: 3, programmeId: $this->programme->id, lga: 'dutse');

        $data = app(MdaDashboardExportBuilder::class)->build(app(DashboardService::class)->forUser($officer, $filter), $filter);

        $this->assertSame("Q3 2026 · {$this->programme->name} · Dutse", $data->subtitle);
    }

    public function test_the_mda_pdf_never_carries_a_beneficiarys_identity(): void
    {
        $officer = $this->user($this->mda, RoleKey::MdaAdmin);
        $data = app(MdaDashboardExportBuilder::class)->build(app(DashboardService::class)->forUser($officer), DashboardFilter::none());

        $html = View::make('reports.pdf', ['data' => $data])->render();
        $this->assertStringNotContainsString('Secretname', $html);
        $this->assertStringNotContainsString('Zzxq', $html);
    }

    /** @return array<string, string> label => value */
    private function section(ReportData $data, string $title): array
    {
        foreach ($data->summary as $section) {
            if ($section->title === $title) {
                return array_column($section->items, 'value', 'label');
            }
        }

        $this->fail("No “{$title}” section");
    }
}
