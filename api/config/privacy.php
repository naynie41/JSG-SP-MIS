<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Data-protection lifecycle (NFR-PRV-01 — NDPA/NDPR)
|--------------------------------------------------------------------------
|
| This file holds the MACHINERY's configuration only. Every LEGAL SPECIFIC —
| which purposes need consent, how long each cohort is retained, what action is
| taken at end-of-life — is a Data Protection Officer decision and lives here as
| configuration, never hard-coded in code. Nothing destructive ships enabled:
| retention is OFF and its policy list is empty until the DPO fills in real
| periods. See docs/SECURITY-FINDINGS.md / SECURITY.md §4.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Consent (NFR-PRV-01, FR-DSH-01)
    |--------------------------------------------------------------------------
    |
    | Consent is captured per PURPOSE and enforced by a GATE. Two gates exist:
    |   - sharing    : cross-MDA read/serve (see config/sharing.php for its switch)
    |   - processing : recording new processing (an intervention) of the subject
    | `processing_requires_consent` is OFF by default so turning it on is a
    | deliberate DPO switch, not a surprise that blocks operations.
    |
    */
    'consent' => [
        'processing_requires_consent' => (bool) env('PRIVACY_PROCESSING_REQUIRES_CONSENT', false),

        // Registered consent purposes. `gate` links a purpose to the gate it drives.
        // Add purposes here rather than in code; labels are shown in the UI.
        'purposes' => [
            'cross_mda_sharing' => ['label' => 'Cross-MDA data sharing', 'gate' => 'sharing'],
            'data_processing' => ['label' => 'Personal data processing', 'gate' => 'processing'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Retention (NFR-PRV-01)
    |--------------------------------------------------------------------------
    |
    | A retention policy selects a COHORT (by beneficiary status + age) and applies
    | an ACTION when a record ages past its period:
    |   - flag      : mark for DPO review; no data is changed.
    |   - aggregate : remove DIRECT identifiers but keep de-identified QUASI fields
    |                 (LGA/ward/gender/DOB) so aggregate statistics remain possible.
    |   - anonymize : remove direct AND quasi identifiers; the row + operational
    |                 history (enrollments, benefit ledger) and audit trail remain.
    |   - delete    : soft-delete the record. If it has operational history it is
    |                 anonymized instead (history must survive) unless
    |                 `delete_hard` is set — see below.
    |
    | RETENTION IS DISABLED AND HAS NO POLICIES BY DEFAULT. The DPO enables it and
    | supplies real periods/cohorts/actions. Example shape is commented out below.
    |
    */
    'retention' => [
        'enabled' => (bool) env('PRIVACY_RETENTION_ENABLED', false),

        // Max records processed per scheduled run (bounded work per tick).
        'batch_limit' => (int) env('PRIVACY_RETENTION_BATCH_LIMIT', 500),

        // A `delete` action never hard-deletes a record that has operational
        // history; it anonymizes instead so aggregates/audit survive. Leave false.
        'delete_hard' => (bool) env('PRIVACY_RETENTION_DELETE_HARD', false),

        'policies' => [
            // ---- EXAMPLE ONLY — DISABLED. Fill in real NDPA/NDPR values. ----
            // [
            //     'key' => 'inactive_10y',
            //     'enabled' => false,
            //     'status' => ['inactive'],            // beneficiary statuses in cohort
            //     'age_field' => 'updated_at',         // column measured for age
            //     'age_days' => 3650,                  // DPO-set period (10 years here)
            //     'action' => 'anonymize',             // flag|aggregate|anonymize|delete
            // ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Anonymization (NFR-PRV-01)
    |--------------------------------------------------------------------------
    |
    | Which beneficiary fields are DIRECT identifiers (always removed) vs QUASI
    | identifiers (removed on full anonymize, kept on `aggregate`). NIN/BVN and
    | their lookup hashes are always cleared. Attached documents are PII files and
    | are purged when `purge_documents` is on. Not-null columns are replaced with
    | `redaction_token` rather than nulled.
    |
    */
    'anonymization' => [
        'direct' => ['first_name', 'middle_name', 'last_name', 'nin', 'bvn', 'phone', 'address'],
        'quasi' => ['date_of_birth', 'gender', 'lga', 'ward'],
        'redaction_token' => '[redacted]',
        'purge_documents' => (bool) env('PRIVACY_ANONYMIZE_PURGE_DOCUMENTS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Right of access — Data Subject Access Request (NFR-PRV-01)
    |--------------------------------------------------------------------------
    |
    | An authorized request assembles the subject's full record + benefit history
    | for export. Fulfilment is a data-controller (owner-MDA) action, permission-
    | gated (`beneficiary.access_request`) and audited. Granting that permission to
    | any non-owner role is a DPO decision.
    |
    */
    'access_request' => [
        'format' => env('PRIVACY_ACCESS_REQUEST_FORMAT', 'json'),
    ],
];
