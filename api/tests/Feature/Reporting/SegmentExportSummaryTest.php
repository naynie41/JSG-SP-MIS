<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Registry\Enums\Gender;
use App\Domain\Registry\Enums\Lga;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use App\Domain\Registry\Models\HouseholdMembership;
use App\Domain\Reporting\Export\ExcelExporter;
use App\Domain\Reporting\Export\PdfExporter;
use App\Domain\Reporting\Export\ReportData;
use App\Domain\Reporting\Models\ReportRun;
use App\Domain\Reporting\Segments\SegmentAccess;
use App\Domain\Reporting\Segments\SegmentDefinition;
use App\Domain\Reporting\Segments\SegmentDimensionRegistry;
use App\Domain\Reporting\Segments\SegmentReportService;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\TestResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

/**
 * The People export's summary block (FR-RPT-03).
 *
 * An exported segment opens with the shape of the population — how many people, and
 * how they divide by gender, age group, household, status, source and LGA — under the
 * state crest. The summary is counted from the rows' own query, and on any tier where
 * the small-cell guard applies it applies to every one of these counts too: a summary
 * is exactly the kind of breakdown the guard exists for.
 */
class SegmentExportSummaryTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mine;

    private Mda $theirs;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mine = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->theirs = Mda::factory()->create(['name' => 'Ministry of Education']);

        $this->users['mdaAdmin'] = $this->user($this->mine, RoleKey::MdaAdmin);
        $this->users['executive'] = $this->user($this->mine, RoleKey::Executive);

        // Five people of this MDA, one of another.
        $head = $this->person($this->mine, 40, Gender::Female);
        $this->person($this->mine, 9, Gender::Female);
        $this->person($this->mine, 25, Gender::Female);
        $this->person($this->mine, 30, Gender::Male);
        $this->person($this->mine, 70, Gender::Male);
        $this->person($this->theirs, 20, Gender::Female);

        $household = Household::factory()->create(['owner_mda_id' => $this->mine->id]);
        HouseholdMembership::query()->create([
            'household_id' => $household->id,
            'beneficiary_id' => $head->id,
            'role_in_household' => 'head',
            'joined_at' => now()->subMonth(),
        ]);
    }

    public function test_an_mda_export_opens_with_counts_of_its_own_people(): void
    {
        $data = $this->exportData('mdaAdmin');

        $this->assertTrue($data->crest);
        $this->assertSame([
            'People in this report' => '5',
            'In a household' => '1',
            'Registered as individuals' => '4',
        ], $this->section($data, 'Overview'));

        // The MDA holds these records, so small counts are shown as they are.
        $this->assertSame(['Women' => '3', 'Men' => '2', Gender::Other->label() => '0', 'Not recorded' => '0'], $this->section($data, 'Gender'));
        $this->assertSame([
            'Children (0–17)' => '1',
            'Youth (18–34)' => '2',
            'Adults (35–59)' => '1',
            'Elderly (60+)' => '1',
            'Not recorded' => '0',
        ], $this->section($data, 'Age group'));
        $this->assertSame([Lga::Dutse->label() => '5'], $this->section($data, 'Local government area'));
    }

    public function test_the_summary_counts_only_the_people_in_the_table(): void
    {
        $data = $this->exportData('mdaAdmin', filters: ['gender' => ['values' => ['female']]]);

        $this->assertSame('3', $this->section($data, 'Overview')['People in this report']);
        $this->assertSame('0', $this->section($data, 'Gender')['Men']);
        $this->assertCount(3, $data->rows);
    }

    public function test_an_aggregate_tier_withholds_small_counts_in_the_summary(): void
    {
        $data = $this->exportData('executive');

        $this->assertSame('6', $this->section($data, 'Overview')['People in this report']);
        $this->assertSame('< 5', $this->section($data, 'Gender')['Women']);
        $this->assertSame('< 5', $this->section($data, 'Gender')['Men']);
        // Zero discloses nothing, so it is not dressed up as withheld.
        $this->assertSame('0', $this->section($data, 'Gender')['Not recorded']);
    }

    public function test_a_segment_too_small_to_publish_prints_only_the_withheld_total(): void
    {
        $data = $this->exportData('executive', filters: ['gender' => ['values' => ['male']]]);

        $this->assertCount(1, $data->summary);
        $this->assertSame(['People in this report' => '< 5'], $this->section($data, 'Overview'));
    }

    public function test_without_the_flag_an_export_is_exactly_as_before(): void
    {
        $data = $this->exportData('mdaAdmin', withSummary: false);

        $this->assertSame([], $data->summary);
        $this->assertFalse($data->crest);
    }

    public function test_the_request_carries_the_flag_onto_the_run_and_the_audit(): void
    {
        Queue::fake();

        $this->send('mdaAdmin', 'POST', '/api/v1/reports/segments/export', ['format' => 'pdf', 'summary' => true])
            ->assertStatus(202);
        $this->send('mdaAdmin', 'POST', '/api/v1/reports/segments/export', ['format' => 'pdf'])
            ->assertStatus(202);

        $runs = ReportRun::query()->where('report_key', ReportRun::KEY_SEGMENT)->orderBy('created_at')->get();
        $this->assertCount(2, $runs);

        $flags = $runs->map(fn (ReportRun $run): bool => (bool) (($run->params ?? [])['summary'] ?? false))->sort()->values()->all();
        $this->assertSame([false, true], $flags);

        // A run that did not ask for a summary stores the same params it always did.
        $plain = $runs->first(fn (ReportRun $run): bool => ! array_key_exists('summary', $run->params ?? []));
        $this->assertNotNull($plain);
    }

    public function test_the_pdf_carries_the_crest_and_the_summary(): void
    {
        $pdf = app(PdfExporter::class)->render($this->exportData('mdaAdmin'));
        $this->assertStringContainsString('/Subtype /Image', $pdf);

        $plain = app(PdfExporter::class)->render($this->exportData('mdaAdmin', withSummary: false));
        $this->assertStringNotContainsString('/Subtype /Image', $plain, 'reports that did not ask for the crest keep the placeholder');
    }

    public function test_a_php_without_gd_still_produces_the_pdf_without_the_crest(): void
    {
        // Dompdf throws when asked to place a PNG without GD. That once failed an MDA's
        // export outright on a worker built before GD was installed.
        $exporter = new class extends PdfExporter
        {
            protected function canEmbedImages(): bool
            {
                return false;
            }
        };

        $pdf = $exporter->render($this->exportData('mdaAdmin'));

        $this->assertStringStartsWith('%PDF', $pdf);
        $this->assertStringNotContainsString('/Subtype /Image', $pdf);
    }

    public function test_excel_puts_the_summary_on_its_own_first_sheet(): void
    {
        $bytes = app(ExcelExporter::class)->render($this->exportData('mdaAdmin'));
        $path = tempnam(sys_get_temp_dir(), 'xlsx');
        file_put_contents($path, $bytes);

        $book = IOFactory::load($path);
        @unlink($path);

        $this->assertSame(['Summary', 'People report'], $book->getSheetNames());
        $this->assertSame('Summary', $book->getActiveSheet()->getTitle());
        $this->assertCount(1, $book->getSheet(0)->getDrawingCollection(), 'the crest is on the summary sheet');
        // The data sheet keeps its header block and table where a filter expects them.
        $this->assertSame('People report', $book->getSheet(1)->getCell('A1')->getValue());
        $this->assertSame('First name', $book->getSheet(1)->getCell('A5')->getValue());
    }

    /* ---------------------------------------------------------------- helpers */

    /** @param array<string, array<string, mixed>> $filters */
    private function exportData(string $user, bool $withSummary = true, array $filters = []): ReportData
    {
        $caller = $this->users[$user];
        $access = SegmentAccess::forUser($caller, app(DashboardScopeResolver::class)->forUser($caller));
        $definition = SegmentDefinition::fromArray(['filters' => $filters], new SegmentDimensionRegistry);

        return app(SegmentReportService::class)->toReportData($definition, $access, $withSummary);
    }

    /** @return array<string, string> label => value */
    private function section(ReportData $data, string $title): array
    {
        foreach ($data->summary as $section) {
            if ($section->title === $title) {
                return array_column($section->items, 'value', 'label');
            }
        }

        $this->fail("No “{$title}” section in the summary");
    }

    private function user(Mda $mda, RoleKey $role): User
    {
        return User::factory()->create([
            'mda_id' => $mda->id,
            'role_id' => Role::where('key', $role->value)->firstOrFail()->id,
        ]);
    }

    /** @param array<string, mixed> $body */
    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    private function person(Mda $owner, int $age, Gender $gender): Beneficiary
    {
        return Beneficiary::factory()->create([
            'owner_mda_id' => $owner->id,
            'date_of_birth' => Carbon::today()->subYears($age)->subMonths(2)->toDateString(),
            'gender' => $gender,
            'lga' => Lga::Dutse,
        ]);
    }
}
