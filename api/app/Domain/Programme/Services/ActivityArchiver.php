<?php

declare(strict_types=1);

namespace App\Domain\Programme\Services;

use App\Domain\Access\Models\User;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Exceptions\ActivityHasPendingWork;
use App\Domain\Programme\Jobs\CompleteEndedActivities;
use App\Domain\Programme\Models\Activity;
use App\Domain\Registry\Enums\ServiceRequestStatus;
use App\Domain\Registry\Models\ServiceRequest;
use Illuminate\Support\Facades\DB;

/**
 * Moves an activity through its lifecycle, and is the ONLY thing that does.
 *
 * The counterpart to {@see ProgrammeArchiver}, and written because activities had no
 * equivalent: `ActivityController@archive` wrote `status = archived` directly, leaving
 * no timestamp, no actor, no reason and no audit entry. An activity could be filed away
 * and there was no way afterwards to say who did it.
 *
 *     active ──timeline ended──▶ completed ──a person decides──▶ archived
 *                                     ▲                              │
 *                                     └───────── restore ────────────┘
 *
 * Completion is automatic ({@see CompleteEndedActivities});
 * archiving is not, and deliberately so. Completion records a fact that already
 * happened — the end date passed — and is safe to infer. Archiving is a filing
 * decision about whether work is finished, which the calendar cannot answer.
 *
 * Both are reversible, both are audited, and both refuse an activity that still owes
 * someone a decision.
 */
class ActivityArchiver
{
    public function __construct(private readonly AuditLogger $audit) {}

    /**
     * Record that the activity's timeline has run out.
     *
     * `$actor` is null for the scheduled sweep: nobody decided this, the calendar did,
     * and attributing it to a person would put a name against a judgement they never
     * made. The audit entry still records the transition and its reason.
     *
     * @throws ActivityHasPendingWork
     */
    public function complete(Activity $activity, ?User $actor = null, string $reason = 'Timeline ended'): Activity
    {
        if ($activity->status !== ActivityStatus::Active) {
            return $activity;
        }

        $this->assertNothingPending($activity);

        DB::transaction(function () use ($activity): void {
            $activity->forceFill([
                'completed_at' => now(),
                'status' => ActivityStatus::Completed,
            ])->save();
        });

        $this->audit->record('activity.completed', $activity, after: ['reason' => $reason], actor: $actor);

        return $activity->fresh() ?? $activity;
    }

    /**
     * File the activity away. Reversible; never a delete (PRD §10, FR-PRG-10).
     *
     * @throws ActivityHasPendingWork
     */
    public function archive(Activity $activity, User $actor, ?string $reason = null): Activity
    {
        if ($activity->isArchived()) {
            return $activity;
        }

        $this->assertNothingPending($activity);

        DB::transaction(function () use ($activity, $actor, $reason): void {
            $activity->forceFill([
                'archived_at' => now(),
                'archived_by' => $actor->id,
                'archive_reason' => $reason,
                'status' => ActivityStatus::Archived,
            ])->save();
        });

        $this->audit->record('activity.archived', $activity, actor: $actor);

        return $activity->fresh() ?? $activity;
    }

    /**
     * Take an activity back out of the archive.
     *
     * Returns it to COMPLETED, not to active — the same reasoning as restoring a
     * programme to draft rather than straight to live. Whether work should resume is a
     * decision for the owning agency, not a side effect of undoing a filing.
     */
    public function restore(Activity $activity, User $actor): Activity
    {
        if (! $activity->isArchived()) {
            return $activity;
        }

        DB::transaction(function () use ($activity): void {
            $activity->forceFill([
                'archived_at' => null,
                'archived_by' => null,
                'archive_reason' => null,
                'status' => ActivityStatus::Completed,
            ])->save();
        });

        $this->audit->record('activity.restored', $activity, actor: $actor);

        return $activity->fresh() ?? $activity;
    }

    /**
     * Requests-to-serve on this activity that nobody has answered.
     *
     * Public so the scheduled sweep can report what it skipped without catching an
     * exception per activity, and so the UI can explain a refusal before it happens.
     */
    public function pendingServiceRequests(Activity $activity): int
    {
        return ServiceRequest::query()
            ->withoutGlobalScopes()
            ->where('activity_id', $activity->id)
            ->where('status', ServiceRequestStatus::Pending->value)
            ->count();
    }

    /**
     * @throws ActivityHasPendingWork
     */
    private function assertNothingPending(Activity $activity): void
    {
        $pending = $this->pendingServiceRequests($activity);

        if ($pending > 0) {
            throw new ActivityHasPendingWork($activity->name, $pending);
        }
    }
}
