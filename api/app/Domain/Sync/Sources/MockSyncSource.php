<?php

declare(strict_types=1);

namespace App\Domain\Sync\Sources;

use App\Domain\Sync\Models\SyncConnector;

/**
 * A documented STAND-IN source used until a real integration is provisioned. It
 * returns raw records from `config('sync.mock_records.<source>')` so the full sync
 * pipeline (validation → dedup → ownership/provenance → idempotency → conflict) is
 * exercisable end-to-end without a live endpoint.
 *
 * 🔶 REAL ACCESS NEEDED: to sync live SOCU / government-system data, provide the
 * endpoint URL, auth method and credentials (as env, keyed by the connector's
 * `credentials_ref`) and the source's field names. Then bind a real {@see SyncSource}
 * (e.g. an HTTP client) in {@see SyncSourceResolver} — nothing else changes.
 */
class MockSyncSource implements SyncSource
{
    public function fetch(SyncConnector $connector): iterable
    {
        /** @var array<int, array<string, mixed>> $records */
        $records = config('sync.mock_records.'.$connector->source->value, []);

        return $records;
    }
}
