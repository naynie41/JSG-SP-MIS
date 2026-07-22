<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Privacy\Services\RetentionService;
use Illuminate\Console\Command;

/**
 * Runs the data-retention policies on demand (NFR-PRV-01). `--dry-run` reports the
 * cohort each policy WOULD act on without changing any data — the safe way to
 * validate a new DPO policy before it goes live. The scheduled equivalent is
 * {@see \App\Domain\Privacy\Jobs\EnforceDataRetention}.
 */
class EnforceDataRetention extends Command
{
    protected $signature = 'privacy:enforce-retention {--dry-run : Report what would happen without changing data}';

    protected $description = 'Apply the configured data-retention policies (flag/aggregate/anonymize/delete)';

    public function handle(RetentionService $retention): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $summary = $retention->run(dryRun: $dryRun);

        if (! $summary->ranPolicies) {
            $this->info('Retention is disabled or has no configured policies — nothing to do.');

            return self::SUCCESS;
        }

        $this->info(($dryRun ? '[dry run] ' : '').'Retention run complete.');
        $this->table(
            ['Flagged', 'Aggregated', 'Anonymized', 'Deleted', 'Total'],
            [[$summary->flagged, $summary->aggregated, $summary->anonymized, $summary->deleted, $summary->total()]],
        );

        return self::SUCCESS;
    }
}
