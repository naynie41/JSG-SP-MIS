<?php

declare(strict_types=1);

namespace App\Domain\Sync\Sources;

use App\Domain\Sync\Models\SyncConnector;

/**
 * Resolves the {@see SyncSource} client for a connector. Today every connector uses
 * the {@see MockSyncSource}; when a real SOCU / government-system endpoint is
 * provisioned, register its client here keyed by the connector's source or
 * `credentials_ref` (credentials from env) — the engine is untouched.
 */
class SyncSourceResolver
{
    public function __construct(private readonly MockSyncSource $mock) {}

    public function for(SyncConnector $connector): SyncSource
    {
        // TODO(phase-7): swap to a real HTTP/DB client per connector->credentials_ref
        // once external access is available. Mock is the safe default meanwhile.
        return $this->mock;
    }
}
