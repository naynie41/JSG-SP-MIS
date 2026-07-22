<?php

declare(strict_types=1);

namespace App\Domain\Sync\Jobs;

use App\Domain\Sync\Enums\SyncTrigger;
use App\Domain\Sync\Models\SyncConnector;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Scheduled fan-out (FR-DSH-02): on each scheduler tick, dispatch a {@see
 * RunSyncConnector} for every enabled connector. The scheduler cadence in
 * bootstrap/app.php IS the sync frequency; RunSyncConnector's uniqueness prevents a
 * new tick from overlapping a still-running sync.
 */
class RunDueSyncConnectors implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        SyncConnector::query()
            ->where('enabled', true)
            ->get()
            ->each(fn (SyncConnector $connector) => RunSyncConnector::dispatch($connector->id, SyncTrigger::Scheduled->value));
    }
}
