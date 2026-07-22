<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Generates a random backup-encryption key (NFR-AVAIL-01). Print it, then store it
 * in the secret manager as BACKUP_ENCRYPTION_KEY — kept SEPARATE from the backup
 * destination's credentials so a compromise of one is not a compromise of both.
 */
class GenerateBackupKey extends Command
{
    protected $signature = 'backup:keygen';

    protected $description = 'Generate a random BACKUP_ENCRYPTION_KEY value';

    public function handle(): int
    {
        $this->line(base64_encode(random_bytes(32)));
        $this->newLine();
        $this->info('Set this as BACKUP_ENCRYPTION_KEY in your secret manager (do not commit it).');

        return self::SUCCESS;
    }
}
