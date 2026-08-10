<?php

declare(strict_types=1);

namespace App\Domain\Registry\Events;

use App\Domain\Registry\Models\ImportBatch;
use App\Domain\Registry\Services\ImportCommitter;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * A confirmed import finished committing (PRD FR-REG-02). Fired from
 * {@see ImportCommitter} once the batch reaches `completed`, so it covers both entry
 * points — the standalone Import Center's queued commit and the activity wizard's
 * atomic confirm.
 *
 * An import is asynchronous: the officer who confirmed it has usually navigated away by
 * the time it lands, which is precisely why the result belongs in the notification bell
 * rather than only on the batch screen.
 *
 * Counts only — never a name or an identifier.
 */
class ImportBatchCompleted
{
    use Dispatchable;

    public function __construct(
        public readonly ImportBatch $batch,
        /** Rows that created a new beneficiary. */
        public readonly int $committed,
        /** Rows linked to an existing record (a request-to-serve was raised). */
        public readonly int $served,
        /** Rows discarded, or flagged and left undecided. */
        public readonly int $skipped,
    ) {}
}
