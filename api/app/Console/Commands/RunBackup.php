<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Ops\Backup\BackupService;
use Illuminate\Console\Command;
use Throwable;

/**
 * Takes an encrypted, offsite backup of the database (+ documents) on demand
 * (NFR-AVAIL-01). The scheduled equivalent runs daily. Exits non-zero on failure so
 * the scheduler/monitoring treats a missed backup as an incident.
 */
class RunBackup extends Command
{
    protected $signature = 'backup:run';

    protected $description = 'Create an encrypted, offsite backup of the database and documents';

    public function handle(BackupService $backups): int
    {
        try {
            $result = $backups->run();
        } catch (Throwable $e) {
            $this->error('Backup failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info('Backup created (encrypted, offsite).');
        $this->table(['Disk', 'Path', 'Size (KB)', 'Duration (s)', 'Documents'], [[
            $result->disk,
            $result->path,
            number_format($result->sizeBytes / 1024, 1),
            round($result->durationSeconds, 2),
            $result->includedDocuments ? 'yes' : 'no',
        ]]);

        return self::SUCCESS;
    }
}
