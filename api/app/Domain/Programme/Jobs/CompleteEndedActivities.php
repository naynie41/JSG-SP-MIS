<?php

declare(strict_types=1);

namespace App\Domain\Programme\Jobs;

use App\Domain\Programme\Enums\ActivityStatus;
use App\Domain\Programme\Exceptions\ActivityHasPendingWork;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Services\ActivityArchiver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Nightly sweep: mark an activity completed once its timeline has run out.
 *
 * Before this, nothing in the system read `ends_on` except one dashboard status light.
 * An activity that finished two years ago stayed `active` for ever unless a person
 * remembered to file it, so every "active activities" figure only ever climbed. The
 * numbers were not wrong about the data; the data was wrong about the world.
 *
 * It does exactly one thing, and stops:
 *
 *  - **It completes. It never archives.** Completion records something that already
 *    happened — the end date passed — which a calendar can settle. Archiving asserts
 *    the work is finished and filed, which it cannot. That decision stays with a
 *    person (owner's decision, 2026-09-23).
 *  - **It skips anything that still owes a decision.** An activity with a pending
 *    request-to-serve is left alone and reported, never forced. Completing it would
 *    leave another MDA's request queued against work nobody is looking at.
 *  - **It is reversible.** Every transition goes through {@see ActivityArchiver}, so
 *    each one is audited and can be undone.
 *
 * Bounded per tick and idempotent: it selects only `active` rows, so a second run the
 * same night finds nothing left to do.
 */
class CompleteEndedActivities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(ActivityArchiver $archiver): void
    {
        if (! config('programme.complete_after_end', true)) {
            return;
        }

        $grace = max(0, (int) config('programme.complete_grace_days', 0));
        $cutoff = Carbon::today()->subDays($grace);

        $due = Activity::query()
            ->withoutGlobalScopes()
            ->where('status', ActivityStatus::Active->value)
            ->whereNotNull('ends_on')
            ->whereDate('ends_on', '<', $cutoff)
            ->orderBy('ends_on')
            ->limit(max(1, (int) config('programme.complete_batch_limit', 500)))
            ->get();

        if ($due->isEmpty()) {
            return;
        }

        $completed = 0;
        $blocked = [];

        foreach ($due as $activity) {
            try {
                $archiver->complete($activity, reason: 'Timeline ended on '.$activity->ends_on?->toDateString());
                $completed++;
            } catch (ActivityHasPendingWork $e) {
                // Not an error: the activity is doing exactly what it should, and
                // someone owes it an answer. Recorded so the queue is visible.
                $blocked[] = ['id' => $activity->id, 'name' => $activity->name, 'pending' => $e->pendingServiceRequests()];
            }
        }

        Log::info('[activities] completed {completed} ended activities, skipped {blocked} with pending requests', [
            'completed' => $completed,
            'blocked' => count($blocked),
            // Names, not just a count: a number nobody can act on is not a report.
            'skipped' => $blocked,
        ]);
    }
}
