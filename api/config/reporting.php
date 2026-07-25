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
];
