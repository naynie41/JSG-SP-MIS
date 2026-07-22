<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Ops\Backup\BackupService;
use Illuminate\Console\Command;
use Throwable;

/**
 * Restore drill (NFR-AVAIL-01): takes a real backup, restores it into a throwaway
 * database, verifies row counts, and checks the whole cycle finished within the RTO.
 * Run it on a schedule (and before go-live) so "we have backups" is proven, not
 * assumed. Exits non-zero if the drill does not pass.
 */
class BackupDrill extends Command
{
    protected $signature = 'backup:drill';

    protected $description = 'Prove recoverability: back up, restore into a scratch DB, verify, and time against the RTO';

    public function handle(BackupService $backups): int
    {
        try {
            $drill = $backups->drill();
        } catch (Throwable $e) {
            $this->error('Restore drill failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->table(['Result', 'Counts match', 'Within RTO', 'Duration (s)', 'RTO (min)', 'RPO (min)', 'Tables'], [[
            $drill->passed ? 'PASS' : 'FAIL',
            $drill->countsMatch ? 'yes' : 'no',
            $drill->withinRto ? 'yes' : 'no',
            round($drill->durationSeconds, 2),
            $drill->rtoMinutes,
            $drill->rpoMinutes,
            count($drill->sourceCounts),
        ]]);

        if (! $drill->passed) {
            $this->error('Restore drill did NOT pass — investigate before relying on backups.');

            return self::FAILURE;
        }

        $this->info('Restore drill passed: the latest backup restores and verifies within the RTO.');

        return self::SUCCESS;
    }
}
