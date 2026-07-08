<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Jobs;

use App\Domain\Reporting\Services\DashboardSnapshotService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Scheduled recompute of the dashboard summary snapshots (PRD FR-RPT-01/02). Runs on
 * the queue so the aggregation never happens in a request. Keeps every scope's
 * snapshot fresh (freshness target: real-time / within 24h, §14).
 */
class RefreshDashboardSnapshots implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(DashboardSnapshotService $snapshots): void
    {
        $snapshots->refreshAll();
    }
}
