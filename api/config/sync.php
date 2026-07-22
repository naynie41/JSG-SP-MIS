<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Data synchronization (FR-DSH-02, FR-REG-08)
    |--------------------------------------------------------------------------
    |
    | Default conflict policy when a synced record matches an existing beneficiary:
    |   - flag_for_review : never modify; record a flagged_conflict for an admin.
    |   - last_write_wins : the source overwrites the fields of the record IT owns.
    | Cross-MDA-owned matches are ALWAYS flagged (never silently modified), whatever
    | the policy. Per-connector policy on the connector row overrides this default.
    |
    */
    'default_conflict_policy' => env('SYNC_DEFAULT_CONFLICT_POLICY', 'flag_for_review'),

    /*
    | Source clients pull raw records from the external system. Real SOCU / government
    | systems are HTTP/DB integrations whose ENDPOINTS + CREDENTIALS come from env (a
    | connector's `credentials_ref` keys into this map) — never hard-coded. Until a
    | real endpoint is provisioned the MockSyncSource is bound (see SyncSourceResolver);
    | dev/test records are supplied under `mock_records`, keyed by source value.
    |
    | 🔶 REAL ACCESS NEEDED: SOCU + each government system's API/DB endpoint, auth
    | method, and credentials, plus their field names (for the adapter alias table).
    */
    'connections' => [
        // 'socu' => ['base_url' => env('SYNC_SOCU_URL'), 'token' => env('SYNC_SOCU_TOKEN')],
    ],

    'mock_records' => [
        // 'socu' => [ ['first_name' => 'Ada', 'nin' => '12345678901', 'id' => 'SOCU-1'], ... ],
    ],
];
