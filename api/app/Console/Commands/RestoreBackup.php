<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Domain\Ops\Backup\BackupService;
use Illuminate\Console\Command;
use Throwable;

/**
 * Restore an encrypted offsite backup into a target connection (NFR-AVAIL-01). This
 * is destructive — it overwrites the target database — so it prompts unless --force
 * is given and defaults the target to a NON-default connection. Intended for real
 * recovery and staging refreshes; the automated {@see BackupDrill} covers routine
 * verification.
 */
class RestoreBackup extends Command
{
    protected $signature = 'backup:restore {artifact : Offsite path of the .zip.enc artifact}
        {--connection= : Target DB connection (defaults to the app default)}
        {--documents-disk= : Restore documents to this disk}
        {--force : Skip the confirmation prompt}';

    protected $description = 'Restore an encrypted offsite backup into a target database (destructive)';

    public function handle(BackupService $backups): int
    {
        $target = (string) ($this->option('connection') ?: config('database.default'));

        if (! $this->option('force') && ! $this->confirm("This OVERWRITES the '{$target}' database. Continue?")) {
            $this->warn('Aborted.');

            return self::SUCCESS;
        }

        try {
            $backups->restoreDatabaseInto(
                (string) $this->argument('artifact'),
                $target,
                $this->option('documents-disk') ? (string) $this->option('documents-disk') : null,
            );
        } catch (Throwable $e) {
            $this->error('Restore failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->info("Restored '{$this->argument('artifact')}' into '{$target}'.");

        return self::SUCCESS;
    }
}
