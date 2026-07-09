<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Jobs;

use App\Domain\Reporting\Services\ReportScheduleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

/**
 * Scheduled sweep of due report schedules (PRD FR-RPT-04). Runs on the shared
 * scheduler/queue; each due schedule generates a report run (which is then delivered
 * to its validated recipients). Never runs a paused schedule.
 */
class RunDueReportSchedules implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(ReportScheduleService $schedules): void
    {
        $schedules->runDue(Carbon::today());
    }
}
