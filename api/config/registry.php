<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Beneficiary list export
    |--------------------------------------------------------------------------
    | Row count above which an export is generated on the queue instead of
    | streamed synchronously. Mirrors the default already compiled into
    | BeneficiaryListExport — this file previously did not exist, so that call
    | was silently falling back. Changing this value changes behaviour.
    */
    'export_sync_max' => (int) env('REGISTRY_EXPORT_SYNC_MAX', 2000),

    /*
    |--------------------------------------------------------------------------
    | Import staleness
    |--------------------------------------------------------------------------
    | How long a batch may sit in a processing state (pending / processing /
    | committing) before the UI stops saying "Processing…" and warns that the
    | background worker may not be running.
    |
    | This is an OPERATIONAL threshold, not a stakeholder decision: parsing a
    | few hundred rows takes seconds, so anything past a minute means the queue
    | is not being consumed. Generous enough that a genuinely large file does
    | not trip it, short enough that nobody watches a dead worker for ten
    | minutes believing work is happening.
    */
    'import' => [
        'stalled_after_seconds' => (int) env('REGISTRY_IMPORT_STALLED_AFTER', 90),
    ],
];
