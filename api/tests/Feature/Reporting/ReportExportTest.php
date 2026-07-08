<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Grievance\Models\Grievance;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Models\ProgrammeFunder;
use App\Domain\Referral\Models\Referral;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Export\CsvExporter;
use App\Domain\Reporting\Export\ExcelExporter;
use App\Domain\Reporting\Export\ReportColumn;
use App\Domain\Reporting\Export\ReportData;
use App\Domain\Reporting\Jobs\GenerateReport;
use App\Domain\Reporting\Reports\ReportBuilder;
use App\Domain\Reporting\Support\DashboardScope;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Testing\TestResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

/**
 * Report export service + standard catalogue (PRD FR-RPT-03): CSV/Excel/PDF output,
 * scope-aware generation, PII masking, permission gating, queued generation with a
 * ready notification, and audited generation + downloads.
 */
class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    private Programme $progA;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);
        $this->users['partner'] = $this->user(null, RoleKey::DevelopmentPartner);
        $this->users['noRole'] = User::factory()->create(['mda_id' => $this->mdaA->id, 'role_id' => null]);

        $aBen = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse']);
        $bBen = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaB->id, 'lga' => 'hadejia']);

        $this->progA = Programme::factory()->individual()->create(['owner_mda_id' => $this->mdaA->id, 'name' => 'Cash Transfer A', 'status' => 'active', 'budget_amount' => 1_000_000]);
        $progB = Programme::factory()->individual()->create(['owner_mda_id' => $this->mdaB->id, 'name' => 'Grant B', 'status' => 'active', 'budget_amount' => 500_000]);
        ProgrammeFunder::create(['programme_id' => $this->progA->id, 'user_id' => $this->users['partner']->id]);

        Benefit::factory()->create(['beneficiary_id' => $aBen->id, 'programme_id' => $this->progA->id, 'mda_id' => $this->mdaA->id, 'monetary_value' => 300_000, 'lga' => 'dutse', 'status' => 'verified']);
        Benefit::factory()->create(['beneficiary_id' => $bBen->id, 'programme_id' => $progB->id, 'mda_id' => $this->mdaB->id, 'monetary_value' => 150_000, 'lga' => 'hadejia', 'status' => 'verified']);

        Referral::create(['beneficiary_id' => $aBen->id, 'from_mda_id' => $this->mdaA->id, 'to_mda_id' => $this->mdaB->id, 'need' => 'Health', 'status' => 'created']);
        Grievance::create(['handling_mda_id' => $this->mdaA->id, 'beneficiary_id' => $aBen->id, 'category' => 'payment', 'channel' => 'walk_in', 'description' => 'x', 'status' => 'open']);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda?->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function sampleData(): ReportData
    {
        return new ReportData('t', 'Test report', 'Standard report', 'State-wide', Carbon::now(),
            [new ReportColumn('name', 'Name'), new ReportColumn('nin', 'NIN', sensitive: true)],
            [['name' => 'Row One', 'nin' => '12345678901']],
        );
    }

    /* --------------------------------------------------------------- format output */

    public function test_generates_each_format_end_to_end(): void
    {
        Storage::fake('local');

        foreach (['csv', 'xlsx', 'pdf'] as $format) {
            $id = $this->send('officerA', 'POST', '/api/v1/reports', ['report_key' => 'benefits_by_programme', 'format' => $format])
                ->assertCreated()->json('data.id');

            // Sync queue → the run is ready with a stored file.
            $this->send('officerA', 'GET', "/api/v1/reports/{$id}")->assertOk()->assertJsonPath('data.status', 'ready');

            $download = $this->send('officerA', 'GET', "/api/v1/reports/{$id}/download")->assertOk();
            $content = $download->streamedContent();

            if ($format === 'csv') {
                $this->assertStringContainsString('Cash Transfer A', $content);
                $this->assertStringContainsString('Deliveries', $content);
            } elseif ($format === 'xlsx') {
                $this->assertStringStartsWith('PK', $content); // zip (xlsx) signature
            } else {
                $this->assertStringStartsWith('%PDF', $content);
                $this->assertGreaterThan(1000, strlen($content));
            }
        }
    }

    public function test_pdf_template_is_branded(): void
    {
        $html = View::make('reports.pdf', ['data' => $this->sampleData()])->render();

        $this->assertStringContainsString('Jigawa State', $html);
        $this->assertStringContainsString('State', $html);       // crest slot placeholder
        $this->assertStringContainsString('Test report', $html); // title
        $this->assertStringContainsString('#2C3512', $html);     // forest brand colour
    }

    /* ----------------------------------------------------------------- masking */

    public function test_sensitive_columns_are_masked_in_every_format(): void
    {
        $data = $this->sampleData();
        $masked = '•••••••8901';

        $csv = app(CsvExporter::class)->render($data);
        $this->assertStringContainsString($masked, $csv);
        $this->assertStringNotContainsString('12345678901', $csv);

        // Excel: reload the produced workbook and read the NIN cell.
        $xlsx = app(ExcelExporter::class)->render($data);
        $path = tempnam(sys_get_temp_dir(), 'rx').'.xlsx';
        file_put_contents($path, $xlsx);
        $value = (string) IOFactory::load($path)->getActiveSheet()->getCell('B6')->getValue();
        @unlink($path);
        $this->assertSame($masked, $value);

        // PDF renders from the same template — assert the source HTML masks it.
        $html = View::make('reports.pdf', ['data' => $data])->render();
        $this->assertStringContainsString($masked, $html);
        $this->assertStringNotContainsString('12345678901', $html);
    }

    /* ------------------------------------------------------------------- scoping */

    public function test_report_rows_respect_scope(): void
    {
        $builder = app(ReportBuilder::class);

        $stateWide = $builder->build('benefits_by_mda', DashboardScope::stateWide());
        $this->assertCount(2, $stateWide->rows); // both MDAs

        $mdaOnly = $builder->build('benefits_by_mda', DashboardScope::mda([$this->mdaA->id]));
        $this->assertCount(1, $mdaOnly->rows);
        $this->assertSame('MDA A', $mdaOnly->rows[0]['dimension']);

        $partner = $builder->build('benefits_by_programme', DashboardScope::partner([$this->progA->id]));
        $this->assertCount(1, $partner->rows); // only the funded programme
        $this->assertSame('Cash Transfer A', $partner->rows[0]['dimension']);
    }

    /* ------------------------------------------------- queue + notification + audit */

    public function test_generation_is_queued(): void
    {
        Queue::fake();

        $this->send('officerA', 'POST', '/api/v1/reports', ['report_key' => 'beneficiaries_by_lga', 'format' => 'csv'])
            ->assertCreated();

        Queue::assertPushed(GenerateReport::class);
    }

    public function test_generation_notifies_requester_and_is_audited(): void
    {
        Storage::fake('local');

        $id = $this->send('officerA', 'POST', '/api/v1/reports', ['report_key' => 'grievance_sla', 'format' => 'csv'])
            ->assertCreated()->json('data.id');

        // In-app "report ready" notification to the requester (FR-NOT-01).
        $this->assertDatabaseHas('notifications', [
            'recipient_user_id' => $this->users['officerA']->id,
            'type' => 'report.ready',
            'related_type' => 'report_run',
            'related_id' => $id,
        ]);

        // Generation is audited.
        $this->assertDatabaseHas('audit_log', ['action' => 'report.generated', 'entity_id' => $id]);

        // Downloading is audited too.
        $this->send('officerA', 'GET', "/api/v1/reports/{$id}/download")->assertOk();
        $this->assertDatabaseHas('audit_log', ['action' => 'report.downloaded', 'entity_id' => $id]);
    }

    /* -------------------------------------------------------- permissions + scope */

    public function test_generation_requires_export_permission(): void
    {
        Storage::fake('local');

        // No-role user cannot generate (no reporting.export).
        $this->send('noRole', 'POST', '/api/v1/reports', ['report_key' => 'beneficiaries_by_lga', 'format' => 'csv'])
            ->assertStatus(403);
    }

    public function test_a_partner_cannot_request_a_coordination_report(): void
    {
        Storage::fake('local');

        // Coordination reports are not in a partner's catalogue.
        $this->send('partner', 'GET', '/api/v1/reports/catalogue')
            ->assertOk()
            ->assertJsonMissing(['key' => 'referral_completion']);

        $this->send('partner', 'POST', '/api/v1/reports', ['report_key' => 'referral_completion', 'format' => 'csv'])
            ->assertStatus(422)->assertJsonPath('error.code', 'REPORT_UNAVAILABLE');
    }

    public function test_a_user_cannot_download_another_users_report(): void
    {
        Storage::fake('local');

        $id = $this->send('officerA', 'POST', '/api/v1/reports', ['report_key' => 'beneficiaries_by_lga', 'format' => 'csv'])
            ->assertCreated()->json('data.id');

        // The partner (a different requester) cannot see or download it.
        $this->send('partner', 'GET', "/api/v1/reports/{$id}/download")->assertStatus(404);
    }
}
