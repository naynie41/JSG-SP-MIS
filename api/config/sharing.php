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
];
