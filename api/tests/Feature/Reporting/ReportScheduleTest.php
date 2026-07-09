<?php

declare(strict_types=1);

namespace Tests\Feature\Reporting;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Jobs\RunDueReportSchedules;
use App\Domain\Reporting\Mail\ScheduledReportMail;
use App\Domain\Reporting\Models\ReportRun;
use App\Domain\Reporting\Models\ReportSchedule;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

/**
 * Scheduled report generation + distribution (PRD FR-RPT-04): schedule a report,
 * generate on schedule, deliver to validated recipients (in-app + email; link or
 * attachment), never deliver out-of-scope data, manage (pause/edit/delete), and audit.
 */
class ReportScheduleTest extends TestCase
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

        $this->mdaA = Mda::factory()->create(['name' => 'MDA A']);
        $this->mdaB = Mda::factory()->create(['name' => 'MDA B']);

        $this->users['officerA'] = $this->user($this->mdaA, RoleKey::MdaOfficer);   // owner
        $this->users['officerA2'] = $this->user($this->mdaA, RoleKey::MdaOfficer);   // same-scope recipient
        $this->users['officerB'] = $this->user($this->mdaB, RoleKey::MdaOfficer);    // out-of-scope recipient
        $this->users['exec'] = $this->user(null, RoleKey::Executive);                // covers everything
        $this->users['noRole'] = User::factory()->create(['mda_id' => $this->mdaA->id, 'role_id' => null]);

        $ben = Beneficiary::factory()->create(['owner_mda_id' => $this->mdaA->id, 'lga' => 'dutse']);
        $prog = Programme::factory()->individual()->create(['owner_mda_id' => $this->mdaA->id, 'status' => 'active']);
        Benefit::factory()->create(['beneficiary_id' => $ben->id, 'programme_id' => $prog->id, 'mda_id' => $this->mdaA->id, 'monetary_value' => 100_000, 'lga' => 'dutse', 'status' => 'verified']);
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

    private function schedulePayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Weekly benefits',
            'report_key' => 'benefits_by_lga',
            'format' => 'csv',
            'frequency' => 'weekly',
            'recipient_user_ids' => [$this->users['officerA2']->id, $this->users['exec']->id],
        ], $overrides);
    }

    /* --------------------------------------------------------- scope validation */

    public function test_recipients_must_cover_the_reports_scope(): void
    {
        // In-scope + oversight recipients are accepted.
        $this->send('officerA', 'POST', '/api/v1/report-schedules', $this->schedulePayload())->assertCreated();

        // A recipient from another MDA cannot receive MDA A's report.
        $this->send('officerA', 'POST', '/api/v1/report-schedules', $this->schedulePayload([
            'recipient_user_ids' => [$this->users['officerB']->id],
        ]))->assertStatus(422)->assertJsonPath('error.code', 'SCHEDULE_INVALID');
    }

    public function test_creation_is_scoped_and_audited(): void
    {
        $id = $this->send('officerA', 'POST', '/api/v1/report-schedules', $this->schedulePayload())
            ->assertCreated()
            ->assertJsonPath('data.scope.kind', 'mda')
            ->json('data.id');

        $this->assertDatabaseHas('audit_log', ['action' => 'report_schedule.created', 'entity_id' => $id]);
    }

    /* ------------------------------------------------- generation + delivery (link) */

    public function test_due_schedule_generates_and_delivers_via_link(): void
    {
        Storage::fake('local');

        $id = $this->send('officerA', 'POST', '/api/v1/report-schedules', $this->schedulePayload())->assertCreated()->json('data.id');

        RunDueReportSchedules::dispatchSync();

        // A run was generated for the schedule, under the owner's scope, and is ready.
        $run = ReportRun::query()->where('schedule_id', $id)->firstOrFail();
        $this->assertSame('ready', $run->status);
        $this->assertSame('mda', $run->scope_kind);
        $this->assertContains($this->users['officerA2']->id, $run->recipient_user_ids);
        $this->assertDatabaseHas('audit_log', ['action' => 'report_schedule.ran', 'entity_id' => $id]);

        // Each validated recipient got an in-app "report ready" notification.
        $this->assertDatabaseHas('notifications', ['recipient_user_id' => $this->users['officerA2']->id, 'type' => 'report.ready', 'related_id' => $run->id]);
        $this->assertDatabaseHas('notifications', ['recipient_user_id' => $this->users['exec']->id, 'type' => 'report.ready']);

        // A recipient can download it; a non-recipient cannot.
        $this->send('officerA2', 'GET', "/api/v1/reports/{$run->id}/download")->assertOk();
        $this->send('officerB', 'GET', "/api/v1/reports/{$run->id}/download")->assertStatus(404);

        // Not generated again the same day.
        RunDueReportSchedules::dispatchSync();
        $this->assertSame(1, ReportRun::query()->where('schedule_id', $id)->count());
    }

    public function test_attachment_delivery_emails_the_file(): void
    {
        Storage::fake('local');
        Mail::fake();

        $id = $this->send('officerA', 'POST', '/api/v1/report-schedules', $this->schedulePayload([
            'delivery' => 'attachment', 'recipient_user_ids' => [$this->users['officerA2']->id],
        ]))->assertCreated()->json('data.id');

        RunDueReportSchedules::dispatchSync();

        // In-app record + the file attached by email to the recipient.
        $this->assertDatabaseHas('notifications', ['recipient_user_id' => $this->users['officerA2']->id, 'type' => 'report.ready']);
        Mail::assertQueued(ScheduledReportMail::class, fn (ScheduledReportMail $m) => $m->hasTo($this->users['officerA2']->email));
    }

    /* --------------------------------------------------------- saved ad-hoc source */

    public function test_can_schedule_a_saved_ad_hoc_definition(): void
    {
        Storage::fake('local');

        $definitionId = $this->send('officerA', 'POST', '/api/v1/report-definitions', [
            'name' => 'Benefits by LGA', 'dataset' => 'benefits', 'group_by' => ['lga'], 'measures' => ['count'],
        ])->assertCreated()->json('data.id');

        $id = $this->send('officerA', 'POST', '/api/v1/report-schedules', [
            'name' => 'Weekly ad-hoc', 'report_definition_id' => $definitionId, 'format' => 'csv', 'frequency' => 'daily',
            'recipient_user_ids' => [$this->users['officerA2']->id],
        ])->assertCreated()->json('data.id');

        RunDueReportSchedules::dispatchSync();

        $this->assertDatabaseHas('report_runs', ['schedule_id' => $id, 'status' => 'ready', 'report_key' => 'adhoc']);
    }

    /* ------------------------------------------------------------------ manage */

    public function test_pause_edit_and_delete(): void
    {
        Storage::fake('local');

        $id = $this->send('officerA', 'POST', '/api/v1/report-schedules', $this->schedulePayload())->assertCreated()->json('data.id');

        // Pause → not generated by the sweep.
        $this->send('officerA', 'PATCH', "/api/v1/report-schedules/{$id}", ['status' => 'paused'])->assertOk()->assertJsonPath('data.status', 'paused');
        RunDueReportSchedules::dispatchSync();
        $this->assertSame(0, ReportRun::query()->where('schedule_id', $id)->count());

        // Edit frequency.
        $this->send('officerA', 'PATCH', "/api/v1/report-schedules/{$id}", ['frequency' => 'monthly'])->assertOk()->assertJsonPath('data.frequency', 'monthly');

        // Delete (audited).
        $this->send('officerA', 'DELETE', "/api/v1/report-schedules/{$id}")->assertOk();
        $this->assertDatabaseMissing('report_schedules', ['id' => $id]);
        $this->assertDatabaseHas('audit_log', ['action' => 'report_schedule.deleted', 'entity_id' => $id]);
    }

    public function test_frequency_due_logic(): void
    {
        $today = Carbon::create(2026, 7, 8);
        $schedule = new ReportSchedule(['status' => 'active', 'frequency' => 'weekly']);

        $schedule->last_run_on = null;
        $this->assertTrue($schedule->dueOn($today));                          // never run → due

        $schedule->last_run_on = $today->copy()->subDays(8);
        $this->assertTrue($schedule->dueOn($today));                          // 8 days ago → due

        $schedule->last_run_on = $today->copy()->subDays(3);
        $this->assertFalse($schedule->dueOn($today));                         // 3 days ago → not due

        $schedule->status = 'paused';
        $schedule->last_run_on = null;
        $this->assertFalse($schedule->dueOn($today));                         // paused → never due
    }

    public function test_managing_schedules_requires_export_permission(): void
    {
        $this->send('noRole', 'POST', '/api/v1/report-schedules', $this->schedulePayload())->assertStatus(403);
    }
}
