<?php

declare(strict_types=1);

namespace App\Domain\Sync\Jobs;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Sync\Enums\SyncTrigger;
use App\Domain\Sync\Models\SyncConnector;
use App\Domain\Sync\Services\SyncEngine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Runs one connector's sync on the queue (FR-DSH-02) — heavy fetch + pipeline work
 * never blocks the request/scheduler cycle. Unique per connector so a scheduled tick
 * never overlaps a still-running sync of the same connector. Per-record idempotency
 * keys mean a retry cannot double-insert.
 */
class RunSyncConnector implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1; // the engine records its own per-record outcome

    public function __construct(
        public readonly string $connectorId,
        public readonly string $trigger = 'scheduled',
        public readonly ?string $triggeredBy = null,
    ) {}

    public function uniqueId(): string
    {
        return $this->connectorId;
    }

    public function handle(SyncEngine $engine): void
    {
        $connector = SyncConnector::query()->find($this->connectorId);
        if ($connector === null || ! $connector->enabled) {
            return;
        }

        $by = $this->triggeredBy !== null
            ? User::query()->withoutGlobalScope(MdaScope::class)->find($this->triggeredBy)
            : null;

        $engine->runConnector($connector, SyncTrigger::from($this->trigger), $by);
    }
}
