<?php

declare(strict_types=1);

namespace Tests\Feature\Programme;

use App\Domain\Access\Enums\RoleKey;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\Role;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Models\AuditLog;
use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Jobs\CompleteEndedActivities;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Services\ActivityArchiver;
use App\Domain\Registry\Enums\ServiceRequestStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\ServiceRequest;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Activity lifecycle: completion by the calendar, archiving by a person (PRD §10).
 *
 * The behaviour worth pinning is mostly what the sweep REFUSES to do. Before this,
 * nothing read `ends_on` at all, so an activity that finished years ago stayed active
 * for ever and every "active activities" figure only climbed. The fix is easy to
 * over-apply — archiving on a timer, or completing something that still owes another
 * agency an answer — so those are the cases with tests on them.
 */
class ActivityLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private Mda $mda;

    private Programme $programme;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->mda = Mda::factory()->create(['name' => 'Ministry of Health']);
        $this->programme = Programme::factory()->individual()->create(['status' => 'active']);
    }

    private function activity(ActivityStatus $status, ?string $endsOn): Activity
    {
        return Activity::factory()->forProgramme($this->programme, $this->mda)->create([
            'status' => $status->value,
            'ends_on' => $endsOn,
        ]);
    }

    private function owner(): User
    {
        return User::factory()->create([
            'mda_id' => $this->mda->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);
    }

    /** Another MDA asking to serve one of this activity's beneficiaries, unanswered. */
    private function pendingRequestOn(Activity $activity): void
    {
        ServiceRequest::create([
            'activity_id' => $activity->id,
            'beneficiary_id' => Beneficiary::factory()->create(['owner_mda_id' => $this->mda->id])->id,
            'from_mda_id' => Mda::factory()->create()->id,
            'to_mda_id' => $this->mda->id,
            'status' => ServiceRequestStatus::Pending->value,
            'reason' => 'Also eligible for our programme',
        ]);
    }

    private function sweep(): void
    {
        app(CompleteEndedActivities::class)->handle(app(ActivityArchiver::class));
    }

    /* ------------------------------------------------------- the nightly sweep */

    public function test_an_activity_whose_timeline_ended_is_completed(): void
    {
        $activity = $this->activity(ActivityStatus::Active, Carbon::yesterday()->toDateString());

        $this->sweep();

        $fresh = $activity->fresh();
        $this->assertSame(ActivityStatus::Completed, $fresh->status);
        $this->assertNotNull($fresh->completed_at, 'Completion must record WHEN, not just that it happened.');
    }

    public function test_an_activity_still_running_is_left_alone(): void
    {
        $activity = $this->activity(ActivityStatus::Active, Carbon::tomorrow()->toDateString());

        $this->sweep();

        $this->assertSame(ActivityStatus::Active, $activity->fresh()->status);
    }

    public function test_an_activity_with_no_end_date_is_never_completed(): void
    {
        $activity = $this->activity(ActivityStatus::Active, null);

        $this->sweep();

        $this->assertSame(ActivityStatus::Active, $activity->fresh()->status, 'An open-ended activity has no timeline to end.');
    }

    public function test_a_draft_is_never_completed_by_the_calendar(): void
    {
        // A draft that was never started has an end date that means nothing. Completing
        // it would claim work happened that nobody ever began.
        $activity = $this->activity(ActivityStatus::Draft, Carbon::yesterday()->toDateString());

        $this->sweep();

        $this->assertSame(ActivityStatus::Draft, $activity->fresh()->status);
    }

    /**
     * The decision that shapes the whole feature: the sweep completes and stops.
     * Archiving asserts the work is finished and filed, which a calendar cannot know.
     */
    public function test_the_sweep_never_archives(): void
    {
        $activity = $this->activity(ActivityStatus::Active, Carbon::today()->subYears(3)->toDateString());

        $this->sweep();
        $this->sweep();

        $fresh = $activity->fresh();
        $this->assertSame(ActivityStatus::Completed, $fresh->status, 'Even three years past its end, it is completed — never archived.');
        $this->assertNull($fresh->archived_at);
    }

    public function test_an_activity_owing_a_request_to_serve_decision_is_skipped(): void
    {
        $activity = $this->activity(ActivityStatus::Active, Carbon::yesterday()->toDateString());
        $this->pendingRequestOn($activity);

        $this->sweep();

        $this->assertSame(
            ActivityStatus::Active,
            $activity->fresh()->status,
            'Completing it would strand another MDA\'s request against work nobody is looking at.',
        );
    }

    public function test_it_completes_the_clean_ones_even_when_another_is_blocked(): void
    {
        $blocked = $this->activity(ActivityStatus::Active, Carbon::yesterday()->toDateString());
        $this->pendingRequestOn($blocked);
        $clean = $this->activity(ActivityStatus::Active, Carbon::yesterday()->toDateString());

        $this->sweep();

        $this->assertSame(ActivityStatus::Active, $blocked->fresh()->status);
        $this->assertSame(ActivityStatus::Completed, $clean->fresh()->status, 'One blocked activity must not stop the sweep.');
    }

    public function test_a_second_run_changes_nothing_and_audits_nothing(): void
    {
        $this->activity(ActivityStatus::Active, Carbon::yesterday()->toDateString());

        $this->sweep();
        $after = AuditLog::query()->where('action', 'activity.completed')->count();

        $this->sweep();

        $this->assertSame($after, AuditLog::query()->where('action', 'activity.completed')->count());
    }

    public function test_the_sweep_can_be_switched_off(): void
    {
        config(['programme.complete_after_end' => false]);
        $activity = $this->activity(ActivityStatus::Active, Carbon::yesterday()->toDateString());

        $this->sweep();

        $this->assertSame(ActivityStatus::Active, $activity->fresh()->status);
    }

    public function test_the_grace_period_holds_an_activity_active(): void
    {
        config(['programme.complete_grace_days' => 30]);
        $activity = $this->activity(ActivityStatus::Active, Carbon::today()->subDays(10)->toDateString());

        $this->sweep();
        $this->assertSame(ActivityStatus::Active, $activity->fresh()->status);

        $this->travel(40)->days();
        $this->sweep();
        $this->assertSame(ActivityStatus::Completed, $activity->fresh()->status);
    }

    /* ------------------------------------------------- the manual archive path */

    /**
     * The gap this release closes: archiving used to write a status and nothing else —
     * no timestamp, no actor, no reason, no audit entry.
     */
    public function test_archiving_records_who_did_it_and_why(): void
    {
        $activity = $this->activity(ActivityStatus::Completed, Carbon::yesterday()->toDateString());
        $owner = $this->owner();

        $this->withToken($owner->createToken('t')->plainTextToken)
            ->postJson("/api/v1/activities/{$activity->id}/archive", ['reason' => 'Programme closed out'])
            ->assertOk()
            ->assertJsonPath('data.status', 'archived');

        $fresh = $activity->fresh();
        $this->assertNotNull($fresh->archived_at);
        $this->assertSame($owner->id, $fresh->archived_by);
        $this->assertSame('Programme closed out', $fresh->archive_reason);

        $this->assertDatabaseHas('audit_log', ['action' => 'activity.archived', 'entity_id' => $activity->id]);
    }

    public function test_archiving_is_refused_while_a_request_to_serve_is_unanswered(): void
    {
        $activity = $this->activity(ActivityStatus::Completed, Carbon::yesterday()->toDateString());
        $this->pendingRequestOn($activity);

        $this->withToken($this->owner()->createToken('t')->plainTextToken)
            ->postJson("/api/v1/activities/{$activity->id}/archive")
            ->assertStatus(422)
            ->assertJsonPath('error.code', 'ACTIVITY_HAS_PENDING_WORK');

        $this->assertSame(ActivityStatus::Completed, $activity->fresh()->status);
    }

    public function test_an_archive_can_be_undone(): void
    {
        $activity = $this->activity(ActivityStatus::Completed, Carbon::yesterday()->toDateString());
        $token = $this->owner()->createToken('t')->plainTextToken;

        $this->withToken($token)->postJson("/api/v1/activities/{$activity->id}/archive")->assertOk();
        $this->withToken($token)->postJson("/api/v1/activities/{$activity->id}/restore")->assertOk();

        $fresh = $activity->fresh();
        // Back to completed, NOT to active: whether work resumes is the agency's call.
        $this->assertSame(ActivityStatus::Completed, $fresh->status);
        $this->assertNull($fresh->archived_at);
        $this->assertNull($fresh->archived_by);
        $this->assertDatabaseHas('audit_log', ['action' => 'activity.restored', 'entity_id' => $activity->id]);
    }

    public function test_archiving_is_owner_mda_only(): void
    {
        $activity = $this->activity(ActivityStatus::Completed, Carbon::yesterday()->toDateString());

        $stranger = User::factory()->create([
            'mda_id' => Mda::factory()->create()->id,
            'role_id' => Role::where('key', RoleKey::MdaAdmin->value)->firstOrFail()->id,
        ]);

        $this->withToken($stranger->createToken('t')->plainTextToken)
            ->postJson("/api/v1/activities/{$activity->id}/archive")
            ->assertForbidden();
    }
}
