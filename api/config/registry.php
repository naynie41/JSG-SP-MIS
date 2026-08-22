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

    /*
    |--------------------------------------------------------------------------
    | Identity-field formats (FR-REG-05)
    |--------------------------------------------------------------------------
    | The ONE place the shapes of NIN, BVN, phone and date of birth are written
    | down. They were previously inline literals in BeneficiaryRules; collected
    | here so a change lands in a single edit and every ingestion door — bulk
    | import, REST intake, sync — moves together.
    |
    | CONFIGURABLE BY DEVELOPERS, NOT ADMIN-EDITABLE. CLAUDE.md §9 makes
    | identity-field handling a locked decision: a wrong NIN length does not fail
    | loudly, it lets two different citizens collide in the deterministic match
    | stage. These are national identifier standards, not stakeholder
    | preferences, so there is deliberately no UI or endpoint that writes them.
    */
    'identity' => [
        // NIN and BVN are both exactly 11 numeric digits (NIMC / NIBSS).
        'nin_digits' => (int) env('REGISTRY_NIN_DIGITS', 11),
        'bvn_digits' => (int) env('REGISTRY_BVN_DIGITS', 11),

        // A Nigerian MSISDN in national form after normalization: 0 + 10 digits.
        // Checked on the NORMALIZED value, so +234…, 00234… and 803… all reduce to
        // the same shape before the length is judged.
        'phone_national_digits' => (int) env('REGISTRY_PHONE_DIGITS', 11),

        // The earliest date of birth treated as real data rather than a typo or a
        // placeholder. The future bound is "today" and is not configurable — a
        // birth date cannot be in the future under any policy.
        'dob_earliest' => (string) env('REGISTRY_DOB_EARLIEST', '1900-01-01'),
    ],
];
