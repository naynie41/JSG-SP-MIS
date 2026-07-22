<?php

declare(strict_types=1);

namespace App\Domain\Ops;

use App\Domain\Ops\Backup\BackupCipher;
use Illuminate\Support\ServiceProvider;

/**
 * Operational concerns (NFR-AVAIL-01): binds the backup cipher with its configured
 * key so the backup service and commands resolve a ready-to-use encryptor. Health /
 * metrics endpoints are plain controllers and need no binding.
 */
class OpsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BackupCipher::class, fn () => new BackupCipher(config('backup.encryption_key')));
    }
}
