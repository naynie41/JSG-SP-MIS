<?php

declare(strict_types=1);

namespace App\Domain\Ops\Backup;

use App\Domain\Audit\Services\AuditLogger;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;
use ZipArchive;

/**
 * Automated, encrypted, offsite backups + verified restore (NFR-AVAIL-01).
 *
 * A run packages the database (driver-aware: pg_dump for Postgres, a file copy for
 * sqlite) and, optionally, the beneficiary-documents disk into a single ZIP, encrypts
 * it with {@see BackupCipher} (a backup is NEVER written unencrypted), and ships it to
 * the configured OFFSITE disk, pruning artifacts past the retention window. `drill()`
 * proves recoverability by restoring the latest artifact into a scratch database and
 * comparing row counts, measured against the RTO. Every run/restore/drill is audited;
 * a failure logs at critical for ops alerting.
 */
class BackupService
{
    public function __construct(
        private readonly BackupCipher $cipher,
        private readonly AuditLogger $audit,
    ) {}

    /** Take an encrypted, offsite backup of the given connection (default app DB). */
    public function run(?string $connection = null): BackupResult
    {
        $connection ??= (string) config('database.default');
        $start = microtime(true);
        $workDir = $this->tempDir();

        try {
            if (! $this->cipher->isConfigured()) {
                throw new RuntimeException('BACKUP_ENCRYPTION_KEY is not set — refusing to write an unencrypted backup.');
            }

            $zipPath = $workDir.'/backup.zip';
            $zip = new ZipArchive;
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new RuntimeException('Could not create the backup archive.');
            }

            $driver = $this->dumpDatabase($connection, $zip);
            $includedDocuments = (bool) config('backup.include_documents', true) && $this->addDocuments($zip);
            $zip->addFromString('manifest.json', (string) json_encode([
                'driver' => $driver,
                'connection' => $connection,
                'app' => config('app.name'),
                'created_at' => Carbon::now()->toIso8601String(),
            ], JSON_PRETTY_PRINT));
            $zip->close();

            $disk = (string) config('backup.disk', 'local');
            $path = trim((string) config('backup.path', 'backups'), '/').'/spmis-backup-'.Carbon::now()->format('Ymd-His').'.zip.enc';
            $encrypted = $this->cipher->encrypt((string) file_get_contents($zipPath));
            Storage::disk($disk)->put($path, $encrypted);

            $this->prune($disk);

            $result = new BackupResult(
                disk: $disk,
                path: $path,
                sizeBytes: strlen($encrypted),
                durationSeconds: microtime(true) - $start,
                includedDocuments: $includedDocuments,
                createdAt: Carbon::now()->toIso8601String(),
            );

            $this->audit->record('backup.created', null, after: $result->toArray());
            Log::info('Backup created', $result->toArray());

            return $result;
        } catch (Throwable $e) {
            $this->audit->record('backup.failed', null, after: ['error' => $e->getMessage()]);
            Log::critical('Backup FAILED', ['error' => $e->getMessage()]);

            throw $e;
        } finally {
            $this->cleanup($workDir);
        }
    }

    /**
     * Restore the database from an offsite artifact into a target connection. The
     * documents payload, if present, is restored to $documentsDisk when given.
     */
    public function restoreDatabaseInto(string $artifactPath, string $targetConnection, ?string $documentsDisk = null): void
    {
        $workDir = $this->tempDir();

        try {
            $envelope = Storage::disk((string) config('backup.disk', 'local'))->get($artifactPath);
            if ($envelope === null) {
                throw new RuntimeException("Backup artifact not found: {$artifactPath}");
            }

            $zipPath = $workDir.'/restore.zip';
            file_put_contents($zipPath, $this->cipher->decrypt($envelope));

            $zip = new ZipArchive;
            if ($zip->open($zipPath) !== true) {
                throw new RuntimeException('Could not open the decrypted backup archive.');
            }
            $manifest = json_decode((string) $zip->getFromName('manifest.json'), true);
            $driver = $manifest['driver'] ?? null;

            if ($driver === 'sqlite') {
                $bytes = $zip->getFromName('database.sqlite');
                $target = (string) config("database.connections.{$targetConnection}.database");
                if ($bytes === false || $target === '' || $target === ':memory:') {
                    throw new RuntimeException('Cannot restore sqlite backup into this target.');
                }
                file_put_contents($target, $bytes);
                DB::purge($targetConnection);
            } elseif ($driver === 'pgsql') {
                $sql = (string) $zip->getFromName('database.sql');
                $this->restorePostgres($sql, $targetConnection);
            } else {
                throw new RuntimeException('Unrecognised backup driver in manifest.');
            }

            if ($documentsDisk !== null) {
                $this->restoreDocuments($zip, $documentsDisk);
            }
            $zip->close();

            $this->audit->record('backup.restored', null, after: ['artifact' => $artifactPath, 'target' => $targetConnection]);
        } finally {
            $this->cleanup($workDir);
        }
    }

    /**
     * Restore-drill (NFR-AVAIL-01): back up, restore into a throwaway database, and
     * verify row counts — all under the RTO. Proves the artifacts are recoverable.
     */
    public function drill(?string $connection = null): DrillResult
    {
        $connection ??= (string) config('database.default');
        $start = microtime(true);

        $result = $this->run($connection);

        $scratchFile = $this->tempDir().'/drill-restore.sqlite';
        touch($scratchFile);
        config(['database.connections.backup_drill_restore' => [
            'driver' => 'sqlite',
            'database' => $scratchFile,
            'prefix' => '',
            'foreign_key_constraints' => false,
        ]]);

        try {
            $this->restoreDatabaseInto($result->path, 'backup_drill_restore');

            $sourceCounts = $this->tableCounts($connection);
            $restoredCounts = $this->tableCounts('backup_drill_restore');
            $countsMatch = $sourceCounts === $restoredCounts;

            $duration = microtime(true) - $start;
            $rto = (int) config('backup.rto_minutes', 240);
            $withinRto = $duration <= $rto * 60;

            $drill = new DrillResult(
                passed: $countsMatch && $withinRto,
                countsMatch: $countsMatch,
                withinRto: $withinRto,
                durationSeconds: $duration,
                rtoMinutes: $rto,
                rpoMinutes: (int) config('backup.rpo_minutes', 1440),
                sourceCounts: $sourceCounts,
                restoredCounts: $restoredCounts,
            );

            $this->audit->record('backup.drill', null, after: $drill->toArray());
            Log::info('Backup restore drill', $drill->toArray());

            return $drill;
        } finally {
            DB::purge('backup_drill_restore');
            @unlink($scratchFile);
        }
    }

    /** @return string the driver name recorded in the manifest */
    private function dumpDatabase(string $connection, ZipArchive $zip): string
    {
        $driver = (string) config("database.connections.{$connection}.driver");

        if ($driver === 'sqlite') {
            $file = (string) config("database.connections.{$connection}.database");
            if ($file === '' || $file === ':memory:' || ! is_file($file)) {
                throw new RuntimeException('Cannot back up an in-memory or missing sqlite database.');
            }
            // Checkpoint any WAL so the copied file is consistent.
            DB::connection($connection)->statement('PRAGMA wal_checkpoint(TRUNCATE)');
            $zip->addFromString('database.sqlite', (string) file_get_contents($file));

            return 'sqlite';
        }

        if ($driver === 'pgsql') {
            $zip->addFromString('database.sql', $this->pgDump($connection));

            return 'pgsql';
        }

        throw new RuntimeException("Unsupported database driver for backup: {$driver}");
    }

    private function pgDump(string $connection): string
    {
        $cfg = (array) config("database.connections.{$connection}");
        $process = new Process([
            (string) config('backup.pg_dump', 'pg_dump'),
            '--no-owner', '--no-privileges', '--clean', '--if-exists',
            '-h', (string) ($cfg['host'] ?? '127.0.0.1'),
            '-p', (string) ($cfg['port'] ?? 5432),
            '-U', (string) ($cfg['username'] ?? 'postgres'),
            '-d', (string) ($cfg['database'] ?? ''),
        ], null, ['PGPASSWORD' => (string) ($cfg['password'] ?? '')]);
        $process->setTimeout(3600);
        $process->mustRun();

        return $process->getOutput();
    }

    private function restorePostgres(string $sql, string $targetConnection): void
    {
        $cfg = (array) config("database.connections.{$targetConnection}");
        $process = new Process([
            (string) config('backup.psql', 'psql'),
            '-h', (string) ($cfg['host'] ?? '127.0.0.1'),
            '-p', (string) ($cfg['port'] ?? 5432),
            '-U', (string) ($cfg['username'] ?? 'postgres'),
            '-d', (string) ($cfg['database'] ?? ''),
        ], null, ['PGPASSWORD' => (string) ($cfg['password'] ?? '')]);
        $process->setTimeout(3600);
        $process->setInput($sql);
        $process->mustRun();
    }

    /** @return bool whether any documents were added */
    private function addDocuments(ZipArchive $zip): bool
    {
        $disk = Storage::disk((string) config('backup.documents_disk', 'local'));
        $added = false;
        foreach ($disk->allFiles() as $file) {
            $contents = $disk->get($file);
            if ($contents !== null) {
                $zip->addFromString('documents/'.$file, $contents);
                $added = true;
            }
        }

        return $added;
    }

    private function restoreDocuments(ZipArchive $zip, string $documentsDisk): void
    {
        $disk = Storage::disk($documentsDisk);
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = (string) $zip->getNameIndex($i);
            if (str_starts_with($name, 'documents/') && ! str_ends_with($name, '/')) {
                $disk->put(substr($name, strlen('documents/')), (string) $zip->getFromIndex($i));
            }
        }
    }

    private function prune(string $disk): void
    {
        $days = (int) config('backup.retention_days', 30);
        if ($days <= 0) {
            return;
        }
        $cutoff = Carbon::now()->subDays($days)->getTimestamp();
        $storage = Storage::disk($disk);
        foreach ($storage->files((string) config('backup.path', 'backups')) as $file) {
            if (str_ends_with($file, '.zip.enc') && $storage->lastModified($file) < $cutoff) {
                $storage->delete($file);
            }
        }
    }

    /**
     * @return array<string, int>
     */
    private function tableCounts(string $connection): array
    {
        $conn = DB::connection($connection);
        $driver = $conn->getDriverName();

        $tables = $driver === 'pgsql'
            ? array_map(fn ($r) => $r->tablename, $conn->select("SELECT tablename FROM pg_tables WHERE schemaname = 'public'"))
            : array_map(fn ($r) => $r->name, $conn->select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%'"));

        $counts = [];
        foreach ($tables as $table) {
            $counts[$table] = (int) $conn->table($table)->count();
        }
        ksort($counts);

        return $counts;
    }

    private function tempDir(): string
    {
        $dir = sys_get_temp_dir().'/spmis-backup-'.Str::uuid()->toString();
        mkdir($dir, 0700, true);

        return $dir;
    }

    private function cleanup(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }
        foreach ((array) glob($dir.'/*') as $file) {
            @unlink((string) $file);
        }
        @rmdir($dir);
    }
}
