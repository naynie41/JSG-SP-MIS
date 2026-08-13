<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Reporting\Models\ReportSchedule;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * The System Administrator console's Reports section. It adds ADMINISTRATIVE datasets
 * to the existing Phase 6 ad-hoc engine — it does not add a second engine — so these
 * tests assert the two things that are new: which scopes may reach an admin dataset
 * (governance, not merely state-wide), and that export/schedule keep honouring the
 * existing permission matrix and audit trail.
 */
class AdminReportTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mdaA;

    private Mda $mdaB;

    /** @var array<string, User> */
    private array $users = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A', 'type' => 'ministry', 'status' => 'active']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B', 'type' => 'agency', 'status' => 'active']);

        $this->users['admin'] = $this->user(null, RoleKey::SystemAdministrator);
        $this->users['exec'] = $this->user(null, RoleKey::Executive);
        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaAdmin);
        $this->users['partner'] = $this->user(null, RoleKey::DevelopmentPartner);

        // Administrative data to aggregate over.
        Programme::factory()->individual()->create(['name' => 'Cash A', 'status' => 'active']);
        Programme::factory()->individual()->create(['name' => 'Grant B', 'status' => 'draft']);

        ImportBatch::create([
            'owner_mda_id' => $this->mdaA->id, 'uploaded_by' => $this->users['officerA']->id,
            'original_filename' => 'a.xlsx', 'stored_path' => 'imports/a.xlsx',
            'source' => 'excel', 'status' => 'completed',
            'total_rows' => 50, 'valid_rows' => 45, 'invalid_rows' => 5, 'committed_rows' => 45,
        ]);
        ImportBatch::create([
            'owner_mda_id' => $this->mdaB->id, 'uploaded_by' => $this->users['officerA']->id,
            'original_filename' => 'b.csv', 'stored_path' => 'imports/b.csv',
            'source' => 'csv', 'status' => 'failed',
            'total_rows' => 10, 'valid_rows' => 0, 'invalid_rows' => 10, 'committed_rows' => 0,
        ]);
    }

    private function user(?Mda $mda, RoleKey $role): User
    {
        return User::factory()->create(['mda_id' => $mda?->id, 'role_id' => Role::where('key', $role->value)->firstOrFail()->id]);
    }

    /**
     * @param  array<string, mixed>  $body
     */
    private function send(string $key, string $method, string $url, array $body = []): TestResponse
    {
        $response = $this->withToken($this->users[$key]->createToken('t')->plainTextToken)->json($method, $url, $body);
        $this->app['auth']->forgetGuards();

        return $response;
    }

    /* ------------------------------------------------------- dataset filtering */

    public function test_admin_datasets_are_listed_only_for_a_system_administrator(): void
    {
        $adminKeys = ['users', 'organizations', 'programme_catalogue', 'duplicates', 'audit', 'imports'];

        $forAdmin = array_column($this->send('admin', 'GET', '/api/v1/reports/adhoc/datasets')->assertOk()->json('data.datasets'), 'key');
        foreach ($adminKeys as $key) {
            $this->assertContains($key, $forAdmin, "admin should see the {$key} dataset");
        }
        // The existing delivery datasets are still there — this extends, not replaces.
        $this->assertContains('benefits', $forAdmin);
        $this->assertContains('beneficiaries', $forAdmin);

        // State-wide oversight is NOT enough: an Executive sees all programme data but
        // never the governance datasets.
        $forExec = array_column($this->send('exec', 'GET', '/api/v1/reports/adhoc/datasets')->assertOk()->json('data.datasets'), 'key');
        foreach ($adminKeys as $key) {
            $this->assertNotContains($key, $forExec, "executive must not see the {$key} dataset");
        }
        $this->assertContains('benefits', $forExec);

        // An MDA sees no governance dataset either — with ONE deliberate exception.
        // `duplicates` is flagged `mda_scopable`: it is simultaneously platform data
        // quality and the MDA's own operational record (the same rows its Duplicate
        // Resolution module shows), so an MDA may report on its own slice, constrained
        // through the owning import batch. The exception is MDA-only, which is why the
        // Executive assertion above still holds for every key.
        $forOfficer = array_column($this->send('officerA', 'GET', '/api/v1/reports/adhoc/datasets')->assertOk()->json('data.datasets'), 'key');
        foreach ($adminKeys as $key) {
            if ($key === 'duplicates') {
                $this->assertContains($key, $forOfficer, 'an MDA may report on its OWN duplicate review rows');

                continue;
            }
            $this->assertNotContains($key, $forOfficer);
        }
    }

    public function test_a_non_admin_cannot_reach_an_admin_dataset_by_naming_it(): void
    {
        foreach (['exec', 'officerA', 'partner'] as $who) {
            $this->send($who, 'POST', '/api/v1/reports/adhoc/preview', [
                'dataset' => 'audit', 'group_by' => ['action'], 'measures' => ['count'],
            ])->assertStatus(422)->assertJsonPath('error.code', 'INVALID_DEFINITION');

            $this->send($who, 'POST', '/api/v1/reports/adhoc/preview', [
                'dataset' => 'users', 'group_by' => ['role'], 'measures' => ['count'],
            ])->assertStatus(422)->assertJsonPath('error.code', 'INVALID_DEFINITION');
        }
    }

    public function test_admin_dataset_catalogue_exposes_no_pii_or_secret_columns(): void
    {
        $json = (string) json_encode($this->send('admin', 'GET', '/api/v1/reports/adhoc/datasets')->assertOk()->json());

        // Identity + credential columns exist on these tables but are never selectable.
        foreach (['nin', 'bvn', 'phone', 'first_name', 'last_name', 'email', 'password', 'mfa_secret', 'payload', 'before', 'after', 'ip_address'] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $json, "{$forbidden} must not be selectable");
        }
    }

    /* ------------------------------------------------------------ aggregation */

    public function test_user_report_aggregates_by_role_without_naming_anyone(): void
    {
        $body = $this->send('admin', 'POST', '/api/v1/reports/adhoc/preview', [
            'dataset' => 'users', 'group_by' => ['role'], 'measures' => ['count'],
        ])->assertOk()->json('data');

        $this->assertGreaterThanOrEqual(4, $body['row_count']); // the four seeded roles
        $labels = array_column($body['rows'], 0);
        $this->assertContains('System Administrator', $labels);

        // Aggregate only: no user's name or email appears anywhere in the output.
        $json = (string) json_encode($body);
        foreach ($this->users as $user) {
            $this->assertStringNotContainsString($user->email, $json);
        }
    }

    public function test_import_report_sums_row_counts_platform_wide(): void
    {
        $body = $this->send('admin', 'POST', '/api/v1/reports/adhoc/preview', [
            'dataset' => 'imports', 'group_by' => ['status'], 'measures' => ['count', 'total_rows', 'committed_rows'],
        ])->assertOk()->json('data');

        $rows = collect($body['rows'])->keyBy(0);
        $this->assertSame(['1', '50', '45'], array_slice($rows['Completed'], 1));
        $this->assertSame(['1', '10', '0'], array_slice($rows['Failed'], 1));
    }

    public function test_audit_report_counts_events_without_exposing_their_payload(): void
    {
        // Any authenticated write produces audit entries via the existing Auditable trait.
        $this->send('admin', 'POST', '/api/v1/mdas', ['name' => 'New MDA', 'type' => 'agency', 'status' => 'active']);

        $body = $this->send('admin', 'POST', '/api/v1/reports/adhoc/preview', [
            'dataset' => 'audit', 'group_by' => ['action'], 'measures' => ['count'],
        ])->assertOk()->json('data');

        $this->assertGreaterThan(0, $body['row_count']);
        $this->assertGreaterThan(0, AuditLog::query()->count());

        // The report is a tally of actions; the before/after payload never leaves the log.
        $this->assertSame(['Action', 'Events'], array_column($body['columns'], 'label'));
    }

    public function test_organization_and_programme_catalogue_reports_aggregate(): void
    {
        $orgs = $this->send('admin', 'POST', '/api/v1/reports/adhoc/preview', [
            'dataset' => 'organizations', 'group_by' => ['type'], 'measures' => ['count'],
        ])->assertOk()->json('data');
        $this->assertSame(2, $orgs['row_count']); // ministry + agency

        $programmes = $this->send('admin', 'POST', '/api/v1/reports/adhoc/preview', [
            'dataset' => 'programme_catalogue', 'group_by' => ['status'], 'measures' => ['count'],
        ])->assertOk()->json('data');
        $this->assertSame(2, $programmes['row_count']); // active + draft
    }

    /* --------------------------------------------------------- export gating */

    public function test_admin_export_runs_through_the_phase_6_engine_and_downloads(): void
    {
        Storage::fake('local');

        $id = $this->send('admin', 'POST', '/api/v1/reports/adhoc', [
            'dataset' => 'imports', 'group_by' => ['status'], 'measures' => ['count', 'total_rows'], 'format' => 'csv',
        ])->assertCreated()->json('data.id');

        $this->send('admin', 'GET', "/api/v1/reports/{$id}")->assertOk()->assertJsonPath('data.status', 'ready');

        $content = $this->send('admin', 'GET', "/api/v1/reports/{$id}/download")->assertOk()->streamedContent();
        $this->assertStringContainsString('Completed', $content);
        $this->assertStringContainsString('50', $content);
    }

    public function test_admin_export_is_offered_in_every_engine_format(): void
    {
        Storage::fake('local');

        foreach (['csv', 'xlsx', 'pdf'] as $format) {
            $this->send('admin', 'POST', '/api/v1/reports/adhoc', [
                'dataset' => 'organizations', 'group_by' => ['type'], 'measures' => ['count'], 'format' => $format,
            ])->assertCreated()->assertJsonPath('data.format', $format);
        }
    }

    public function test_export_of_an_admin_dataset_still_requires_export_permission(): void
    {
        Storage::fake('local');

        $noRole = User::factory()->create(['mda_id' => null, 'role_id' => null]);
        $this->users['noRole'] = $noRole;

        $this->send('noRole', 'POST', '/api/v1/reports/adhoc', [
            'dataset' => 'imports', 'measures' => ['count'], 'format' => 'csv',
        ])->assertStatus(403);
    }

    public function test_a_queued_admin_export_still_resolves_its_dataset(): void
    {
        Storage::fake('local');

        // The generation job re-validates the definition against the run's REHYDRATED
        // scope. If governance were not captured on the run, this would fail there.
        $id = $this->send('admin', 'POST', '/api/v1/reports/adhoc', [
            'dataset' => 'users', 'group_by' => ['status'], 'measures' => ['count'], 'format' => 'csv',
        ])->assertCreated()->json('data.id');

        $this->send('admin', 'GET', "/api/v1/reports/{$id}")
            ->assertOk()
            ->assertJsonPath('data.status', 'ready'); // not 'failed'
    }

    /* ----------------------------------------------------- scheduling + delivery */

    /** Save an admin ad-hoc definition through the existing endpoint. */
    private function savedDefinition(string $name, string $dataset, string $groupBy): string
    {
        return $this->send('admin', 'POST', '/api/v1/report-definitions', [
            'name' => $name, 'dataset' => $dataset, 'group_by' => [$groupBy], 'measures' => ['count'],
        ])->assertCreated()->json('data.id');
    }

    public function test_an_admin_report_can_be_scheduled_and_is_audited(): void
    {
        $definitionId = $this->savedDefinition('Access review', 'users', 'role');

        $id = $this->send('admin', 'POST', '/api/v1/report-schedules', [
            'name' => 'Weekly access review',
            'report_definition_id' => $definitionId,
            'format' => 'xlsx', 'frequency' => 'weekly', 'delivery' => 'link',
            'recipient_user_ids' => [$this->users['admin']->id],
        ])->assertCreated()->json('data.id');

        $schedule = ReportSchedule::findOrFail($id);
        $this->assertTrue((bool) $schedule->scope_governance, 'the governance axis must be captured on the schedule');
        $this->assertTrue($schedule->toScope()->includesGovernanceData());

        $this->assertDatabaseHas('audit_log', ['action' => 'report_schedule.created']);
    }

    public function test_an_admin_report_cannot_be_scheduled_to_a_non_admin_recipient(): void
    {
        // The Executive is state-wide and would "cover" any ordinary state-wide report,
        // but a governance report is not theirs to receive.
        $auditDefinition = $this->savedDefinition('Audit digest', 'audit', 'action');

        $this->send('admin', 'POST', '/api/v1/report-schedules', [
            'name' => 'Leaky audit digest',
            'report_definition_id' => $auditDefinition,
            'format' => 'csv', 'frequency' => 'weekly', 'delivery' => 'link',
            'recipient_user_ids' => [$this->users['exec']->id],
        ])->assertStatus(422)->assertJsonPath('error.code', 'SCHEDULE_INVALID');

        $this->send('admin', 'POST', '/api/v1/report-schedules', [
            'name' => 'Leaky audit digest',
            'report_definition_id' => $auditDefinition,
            'format' => 'csv', 'frequency' => 'weekly', 'delivery' => 'link',
            'recipient_user_ids' => [$this->users['officerA']->id],
        ])->assertStatus(422)->assertJsonPath('error.code', 'SCHEDULE_INVALID');

        // The same schedule to an administrator is fine.
        $this->send('admin', 'POST', '/api/v1/report-schedules', [
            'name' => 'Audit digest',
            'report_definition_id' => $auditDefinition,
            'format' => 'csv', 'frequency' => 'weekly', 'delivery' => 'link',
            'recipient_user_ids' => [$this->users['admin']->id],
        ])->assertCreated();
    }

    public function test_a_state_wide_scope_does_not_cover_a_governance_report(): void
    {
        $exec = app(DashboardScopeResolver::class)->forUser($this->users['exec']);
        $admin = app(DashboardScopeResolver::class)->forUser($this->users['admin']);

        $this->assertTrue($exec->isStateWide());
        $this->assertFalse($exec->includesGovernanceData());
        $this->assertTrue($admin->includesGovernanceData());

        $this->assertFalse($exec->covers($admin), 'an executive must not cover a governance scope');
        $this->assertTrue($admin->covers($exec), 'an admin covers everything an executive sees');
        $this->assertTrue($admin->covers($admin));
    }
}
