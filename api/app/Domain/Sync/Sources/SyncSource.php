<?php

declare(strict_types=1);

namespace App\Domain\Sync\Sources;

use App\Domain\Sync\Models\SyncConnector;

/**
 * Pulls RAW records from an external/source system for a connector (FR-DSH-02). Each
 * raw record is the source's own shape — the connector's {@see RegistrationSourceAdapter}
 * maps it onto the canonical schema before the shared import pipeline runs. This is the
 * only seam a real integration (SOCU, a government system) implements; the sync engine,
 * validation, dedup and ownership rules never change.
 */
interface SyncSource
{
    /**
     * @return iterable<int, array<string, mixed>> raw source records
     */
    public function fetch(SyncConnector $connector): iterable;
}
