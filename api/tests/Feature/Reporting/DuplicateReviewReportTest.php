<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Models\ImportRow;
use App\Domain\Reporting\DuplicateReview\DuplicateReviewFilter;
use App\Domain\Reporting\DuplicateReview\DuplicateReviewReport;
use App\Domain\Reporting\Export\PdfExporter;
use App\Domain\Reporting\Export\ReportColumn;
use App\Domain\Reporting\Export\ReportSummarySection;
use App\Domain\Reporting\Models\ReportRun;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The duplicate review report (FR-DUP).
 *
 * It replaced a generic group-by builder that could only count rows. What an MDA needs
 * from it is where its match queue stands — what is waiting, for how long, what was
 * decided, which upload the backlog came from — for its OWN uploads, and never the
 * identity of anyone matched.
 */
class DuplicateReviewReportTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mine;

    private Mda $theirs;

    /** @var array<string, User> */
    private array $users = [];

    private int $rowNumber = 0;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mine = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->theirs = Mda::factory()->create(['name' => 'Ministry of Education']);

        $this->users['mdaAdmin'] = $this->user($this->mine, RoleKey::MdaAdmin);
        $this->users['executive'] = $this->user($this->mine, RoleKey::Executive);
        $this->users['partner'] = $this->user($this->mine, RoleKey::DevelopmentPartner);
        $this->users['sysAdmin'] = $this->user($this->mine, RoleKey::SystemAdministrator);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /* ------------------------------------------------------------- the queue */

    public function test_it_reports_where_the_queue_stands_for_the_mdas_own_uploads(): void
    {
        $open = $this->batch($this->mine, 'preview_ready', 'dutse-march.csv');
        $done = $this->batch($this->mine, 'completed', 'gumel-february.csv');

        $this->row($open, 'exact');
        $this->row($open, 'probable', 'link', decidedAt: now());
        $this->row($open, 'probable', 'skip', decidedAt: now());
        $this->row($open, 'none', 'new'); // not a match: never in the review queue
        $this->row($done, 'probable'); // the upload finished without a decision
        $this->row($this->batch($this->theirs, 'preview_ready', 'their-upload.csv'), 'exact');

        $response = $this->send('mdaAdmin', 'GET', '/api/v1/reports/duplicate-review')->assertOk();

        $response->assertJsonPath('data.totals', [
            'surfaced' => 4,
            'exact' => 1,
            'probable' => 3,
            'decided' => 2,
            'awaiting' => 1,
            'closed_undecided' => 1,
        ]);
        $response->assertJsonPath('data.decisions', ['new' => 0, 'link' => 1, 'own' => 0, 'skip' => 1]);
        $response->assertJsonPath('data.batches_total', 2);

        // The upload holding the backlog leads; another MDA's upload is not listed.
        $this->assertSame('dutse-march.csv', $response->json('data.batches.0.file'));
        $this->assertSame(1, $response->json('data.batches.0.awaiting'));
        $this->assertSame(1, $response->json('data.batches.1.closed_undecided'));
        $this->assertSame(0, $response->json('data.batches.1.awaiting'));
        $this->assertStringNotContainsString('their-upload.csv', $response->getContent());
    }

    public function test_waiting_matches_are_banded_by_how_long_ago_they_were_found(): void
    {
        Carbon::setTestNow('2026-09-14 10:00:00');
        $open = $this->batch($this->mine);

        $this->row($open, 'exact', foundAt: now()->subDays(2));
        $this->row($open, 'probable', foundAt: now()->subDays(10));
        $this->row($open, 'probable', foundAt: now()->subDays(40));
        // Decided: old, but no longer waiting.
        $this->row($open, 'probable', 'skip', foundAt: now()->subDays(40), decidedAt: now()->subDays(39));

        $response = $this->send('mdaAdmin', 'GET', '/api/v1/reports/duplicate-review')->assertOk();

        $response->assertJsonPath('data.waiting', [
            ['key' => 'recent', 'label' => 'Under 7 days', 'count' => 1],
            ['key' => 'weeks', 'label' => '7 to 30 days', 'count' => 1],
            ['key' => 'old', 'label' => 'Over 30 days', 'count' => 1],
        ]);
    }

    public function test_the_waiting_bands_are_configuration(): void
    {
        config(['reporting.duplicate_review_waiting_days' => [3, 14]]);
        $this->row($this->batch($this->mine), 'exact');

        $this->send('mdaAdmin', 'GET', '/api/v1/reports/duplicate-review')
            ->assertOk()
            ->assertJsonPath('data.waiting.0.label', 'Under 3 days')
            ->assertJsonPath('data.waiting.2.label', 'Over 14 days');
    }

    public function test_time_to_decide_is_the_median_not_the_mean(): void
    {
        Carbon::setTestNow('2026-09-14 10:00:00');
        $open = $this->batch($this->mine);
        $found = now()->subDays(3);

        $this->row($open, 'probable', 'link', foundAt: $found, decidedAt: $found->copy()->addHours(2));
        $this->row($open, 'probable', 'skip', foundAt: $found, decidedAt: $found->copy()->addHours(4));
        // One slow decision would drag a mean to 12 hours.
        $this->row($open, 'exact', 'skip', foundAt: $found, decidedAt: $found->copy()->addHours(30));

        $response = $this->send('mdaAdmin', 'GET', '/api/v1/reports/duplicate-review')->assertOk();

        $this->assertEquals(4.0, $response->json('data.median_hours_to_decide'));
    }

    public function test_no_decisions_means_no_median_rather_than_zero(): void
    {
        $this->row($this->batch($this->mine), 'exact');

        $this->send('mdaAdmin', 'GET', '/api/v1/reports/duplicate-review')
            ->assertOk()
            ->assertJsonPath('data.median_hours_to_decide', null);
    }

    public function test_band_and_dates_narrow_the_report(): void
    {
        $open = $this->batch($this->mine);
        $this->row($open, 'exact', foundAt: Carbon::parse('2026-09-01 09:00'));
        $this->row($open, 'probable', foundAt: Carbon::parse('2026-09-10 09:00'));

        $this->send('mdaAdmin', 'GET', '/api/v1/reports/duplicate-review?band=probable')
            ->assertOk()->assertJsonPath('data.totals.surfaced', 1)->assertJsonPath('data.totals.probable', 1);

        $this->send('mdaAdmin', 'GET', '/api/v1/reports/duplicate-review?date_from=2026-09-05')
            ->assertOk()->assertJsonPath('data.totals.surfaced', 1)->assertJsonPath('data.totals.probable', 1);

        $this->send('mdaAdmin', 'GET', '/api/v1/reports/duplicate-review?date_to=2026-09-05')
            ->assertOk()->assertJsonPath('data.totals.surfaced', 1)->assertJsonPath('data.totals.exact', 1);
    }

    public function test_narrowing_that_makes_no_sense_is_refused(): void
    {
        $this->send('mdaAdmin', 'GET', '/api/v1/reports/duplicate-review?band=none')->assertStatus(422);
        $this->send('mdaAdmin', 'GET', '/api/v1/reports/duplicate-review?date_from=2026-09-10&date_to=2026-09-01')
            ->assertStatus(422);
    }

    /* --------------------------------------------------------------- privacy */

    public function test_it_never_returns_a_matched_persons_identity(): void
    {
        $this->row($this->batch($this->mine), 'exact');

        $content = (string) $this->send('mdaAdmin', 'GET', '/api/v1/reports/duplicate-review')->assertOk()->getContent();

        $this->assertStringNotContainsString('Zainab', $content);
        $this->assertStringNotContainsString('Garba', $content);
        $this->assertStringNotContainsString('12345678901', $content);
    }

    public function test_a_scope_without_review_data_is_refused_not_shown_an_empty_queue(): void
    {
        $this->row($this->batch($this->mine), 'exact');

        $this->send('partner', 'GET', '/api/v1/reports/duplicate-review')->assertForbidden();
        $this->send('executive', 'GET', '/api/v1/reports/duplicate-review')->assertForbidden();
        $this->send('partner', 'POST', '/api/v1/reports/duplicate-review/export', ['format' => 'pdf'])->assertForbidden();
    }

    public function test_governance_sees_every_mdas_queue(): void
    {
        $this->row($this->batch($this->mine), 'exact');
        $this->row($this->batch($this->theirs), 'probable');

        $this->send('sysAdmin', 'GET', '/api/v1/reports/duplicate-review')
            ->assertOk()
            ->assertJsonPath('data.totals.surfaced', 2);
    }

    /* ----------------------------------------------------------------- export */

    public function test_the_export_is_queued_audited_and_keeps_the_narrowing(): void
    {
        Queue::fake();

        $this->send('mdaAdmin', 'POST', '/api/v1/reports/duplicate-review/export', ['band' => 'exact', 'format' => 'pdf'])
            ->assertStatus(202);

        $run = ReportRun::query()->where('report_key', ReportRun::KEY_DUPLICATE_REVIEW)->firstOrFail();
        $this->assertSame(['filters' => ['band' => 'exact']], $run->params);
        $this->assertSame('pdf', $run->format);

        $this->assertTrue(
            AuditLog::query()->where('action', 'report.duplicate_review_exported')->exists(),
            'pulling the review queue into a file must leave an audit record',
        );
    }

    public function test_the_file_opens_with_the_queue_summary_under_the_crest(): void
    {
        $open = $this->batch($this->mine, 'preview_ready', 'dutse-march.csv');
        $this->row($open, 'exact');
        $this->row($open, 'probable', 'own', decidedAt: now());

        $scope = app(DashboardScopeResolver::class)->forUser($this->users['mdaAdmin']);
        $data = app(DuplicateReviewReport::class)->toReportData($scope, new DuplicateReviewFilter);

        $this->assertTrue($data->crest);
        $this->assertSame(
            ['Matches found', 'Review progress', 'Decisions taken', 'How long matches have waited'],
            array_map(static fn (ReportSummarySection $s): string => $s->title, $data->summary),
        );
        // An MDA's own report has no MDA column: every row would say the same thing.
        $this->assertNotContains('mda', array_map(static fn (ReportColumn $c): string => $c->key, $data->columns));
        $this->assertSame('dutse-march.csv', $data->rows[0]['file']);

        $pdf = app(PdfExporter::class)->render($data);
        $this->assertStringContainsString('/Subtype /Image', $pdf, 'the crest is embedded in the PDF');
    }

    /* ---------------------------------------------------------------- helpers */

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

    private function batch(Mda $owner, string $status = 'preview_ready', string $file = 'upload.csv'): ImportBatch
    {
        return ImportBatch::create([
            'owner_mda_id' => $owner->id,
            'original_filename' => $file,
            'stored_path' => 'imports/'.$file,
            'source' => 'csv',
            'status' => $status,
        ]);
    }

    private function row(ImportBatch $batch, string $band, ?string $resolution = null, ?Carbon $foundAt = null, ?Carbon $decidedAt = null): ImportRow
    {
        $row = ImportRow::create([
            'import_batch_id' => $batch->id,
            'row_number' => ++$this->rowNumber,
            'payload' => ['first_name' => 'Zainab', 'last_name' => 'Garba', 'nin' => '12345678901'],
            'is_valid' => true,
            'match_band' => $band,
            'resolution' => $resolution,
            'resolved_at' => $decidedAt,
        ]);

        if ($foundAt !== null) {
            $row->created_at = $foundAt;
            $row->save();
        }

        return $row;
    }
}
