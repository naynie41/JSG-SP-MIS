<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Executive reporting (FR-RPT-01/02) — Phase 6E aggregation parameters
|--------------------------------------------------------------------------
|
| Stakeholder-owned thresholds for the executive metrics. They are configuration
| (never hard-coded): age-band cut points, the programme + coverage traffic-light
| bands, the "this period" window, and how many months of trend history to keep.
| Traffic-light/coverage bands are absolute (count/target) — NEVER a % of
| population (we hold no population baseline; that is a deferred slot).
|
*/

return [
    /*
    | Minimum group size in AGGREGATE report output (NFR-PRV-01).
    |
    | A count of 1 in "female, 20-25, one ward" identifies a person to anyone local, and
    | a filter builder makes that trivial to reach. Below this number, a group publishes
    | no count. Configurable because how much disclosure risk is acceptable is a
    | stakeholder/DPO decision, not an engineering one (CLAUDE.md §8) — set it to 0 only
    | with that decision recorded.
    |
    | It does NOT apply to an MDA segmenting its own beneficiaries: it already holds
    | those records, so there is nothing to re-identify.
    */
    'min_cell_size' => (int) env('REPORTING_MIN_CELL_SIZE', 5),

    // Segment-builder table page size, and the ceiling on a single preview.
    'segment_page_size' => (int) env('REPORTING_SEGMENT_PAGE_SIZE', 50),

    // Above this many matching rows, an export is queued rather than built in-request.
    'segment_sync_max' => (int) env('REPORTING_SEGMENT_SYNC_MAX', 2000),
    // "New registrations this period" window.
    'current_period_days' => (int) env('REPORTING_PERIOD_DAYS', 30),

    // Months of periodised trend history to precompute.
    'trend_months' => (int) env('REPORTING_TREND_MONTHS', 12),

    // Age bands derived from date_of_birth: label => [min_age_inclusive, max_age_exclusive|null].
    // Contiguous + ascending; records without a DOB are counted as "unknown".
    'age_bands' => [
        'children' => [0, 18],
        'youth' => [18, 35],
        'adults' => [35, 60],
        'elderly' => [60, null],
    ],

    // Programme traffic-light by completion (reached ÷ target): green ≥ green_min,
    // yellow ≥ yellow_min, else red. A programme with no target scores "unrated".
    'programme_traffic_light' => [
        'green_min' => (float) env('REPORTING_PROGRAMME_GREEN', 0.8),
        'yellow_min' => (float) env('REPORTING_PROGRAMME_YELLOW', 0.5),
    ],

    // Coverage banding by ABSOLUTE beneficiaries per LGA/ward (not population %):
    // green ≥ green_min, yellow ≥ yellow_min, else red.
    'coverage_bands' => [
        'green_min' => (int) env('REPORTING_COVERAGE_GREEN', 1000),
        'yellow_min' => (int) env('REPORTING_COVERAGE_YELLOW', 250),
    ],

    // Funded-programme delivery STATUS (Phase 6P) — a four-state traffic light derived
    // from completion (reached ÷ target) AND the timeline (delivery end date):
    //   past end date → completion ≥ completed_min ? Completed : Delayed
    //   still in timeline → completion ≥ on_track_min ? On Track
    //                       : completion ≥ at_risk_min ? At Risk : Delayed
    // A programme with no target is "unrated" (cannot be scored without a denominator).
    'programme_status' => [
        'completed_min' => (float) env('REPORTING_STATUS_COMPLETED', 0.9),
        'on_track_min' => (float) env('REPORTING_STATUS_ON_TRACK', 0.8),
        'at_risk_min' => (float) env('REPORTING_STATUS_AT_RISK', 0.5),
    ],
];
