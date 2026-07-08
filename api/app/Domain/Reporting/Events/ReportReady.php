<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Events;

use App\Domain\Reporting\Models\ReportRun;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Fired when a queued report finishes generating (PRD FR-RPT-03/FR-NOT-01). The
 * notification subscriber alerts the requester (in-app + email) with a deep link to
 * the run, so heavy reports don't block the request.
 */
class ReportReady
{
    use Dispatchable;

    public function __construct(public readonly ReportRun $run) {}
}
