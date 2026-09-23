<?php

declare(strict_types=1);

/**
 * Programme and activity lifecycle (PRD §10, FR-PRG-10).
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Completing activities whose timeline has ended
    |--------------------------------------------------------------------------
    |
    | A nightly sweep moves an activity from `active` to `completed` once `ends_on`
    | has passed (see Domain\Programme\Jobs\CompleteEndedActivities). Without it an
    | activity that finished years ago stays `active` for ever, and every "active
    | activities" count on every dashboard only ever climbs.
    |
    | It NEVER archives. Archiving asserts that work is finished and filed away —
    | a judgement the calendar cannot make — so it stays a human decision
    | (owner's decision, 2026-09-23). If that is ever revisited, the archive step
    | belongs beside this switch, not inside the completion sweep.
    |
    | The sweep also skips any activity that still owes a decision (a pending
    | request-to-serve) and reports it rather than forcing it.
    */
    'complete_after_end' => (bool) env('PROGRAMME_AUTO_COMPLETE', true),

    /*
    | Days to wait after `ends_on` before completing.
    |
    | 0 means "the day after it ended". Raise it if delivery records routinely
    | arrive late and an agency needs the activity to stay active while they land —
    | an operational preference, not a legal retention period, and nothing is
    | deleted or hidden either way.
    */
    'complete_grace_days' => (int) env('PROGRAMME_COMPLETE_GRACE_DAYS', 0),

    /*
    | Maximum activities processed per nightly tick, so one sweep cannot run away
    | on a large backlog. The first run after this ships is the big one; it will
    | take a few nights to clear if there are more than this many.
    */
    'complete_batch_limit' => (int) env('PROGRAMME_COMPLETE_BATCH_LIMIT', 500),

];
