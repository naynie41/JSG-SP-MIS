<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-MDA data-sharing consent (FR-DSH-01, NFR-PRV-01)
    |--------------------------------------------------------------------------
    |
    | When true, a non-owner MDA's cross-MDA READ/SERVE of a beneficiary (via an
    | accepted Service Request grant) is additionally gated on the beneficiary's
    | recorded sharing consent — the grant is only EFFECTIVE while consent is
    | granted. Ownership and oversight (cross-mda.view) reads are NOT consent-gated
    | (the owner already holds the data; oversight is a legal M&E mandate).
    |
    | Whether consent is legally required, and for which purposes, is a Data
    | Protection Officer (NDPA/NDPR) decision. Default ON = conservative.
    |
    */
    'cross_mda_requires_consent' => (bool) env('SHARING_CROSS_MDA_REQUIRES_CONSENT', true),

    /*
    |--------------------------------------------------------------------------
    | Administrative cross-MDA grants (FR-UAM-03)
    |--------------------------------------------------------------------------
    |
    | FR-UAM-03 sanctions an explicit cross-MDA grant beyond request-to-serve: an
    | administrator gives a named user access to another MDA's scoped data, with a
    | recorded reason and an optional expiry. It is WIDER than a service grant — it
    | opens the MDA, not one beneficiary — so it is the most consequential sharing
    | basis in the system.
    |
    | Whether it is additionally gated on the subject's sharing consent is a Data
    | Protection Officer decision, and the two defensible readings differ:
    |
    |   ON  (default) — FR-DSH-01 governs ALL cross-MDA sharing by ownership AND
    |                   consent; an administrative act does not override the subject.
    |   OFF           — treat it like oversight: a deliberate, reasoned, expiring
    |                   administrative grant under a legal mandate, as with M&E.
    |
    | Defaulted ON (conservative). Turning it off is a recorded DPO decision.
    |
    */
    'admin_grant_requires_consent' => (bool) env('SHARING_ADMIN_GRANT_REQUIRES_CONSENT', true),
];
