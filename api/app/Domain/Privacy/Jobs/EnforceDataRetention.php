<?php

declare(strict_types=1);

namespace App\Domain\Privacy\Jobs;

use App\Domain\Privacy\Services\RetentionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Scheduled enforcement of the data-retention policies (NFR-PRV-01). Runs on the
 * shared queue; a no-op unless retention is enabled and policies are configured.
 * Unique so overlapping ticks never double-process a cohort. The run is audited by
 * the service.
 */
class EnforceDataRetention implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(RetentionService $retention): void
    {
        $retention->run(dryRun: false);
    }
}
