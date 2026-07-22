<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Backups & disaster recovery (NFR-AVAIL-01)
|--------------------------------------------------------------------------
|
| Automated, ENCRYPTED, OFFSITE backups of the database and beneficiary
| documents. A backup is NEVER written unencrypted — the encryption key is
| required. The offsite destination is a Flysystem disk (use 's3' or an
| S3-compatible endpoint in production). RPO/RTO targets are asserted by the
| restore drill (`php artisan backup:drill`). See docs/SCALE-AND-AVAILABILITY.md.
|
*/

return [

    // Offsite destination (a disk from config/filesystems.php). Production MUST
    // be an object store in a DIFFERENT failure domain than the DB host ('s3').
    // 'local' is for dev/CI only and is NOT offsite.
    'disk' => env('BACKUP_DISK', 'local'),
    'path' => env('BACKUP_PATH', 'backups'),

    // AES-256 encryption. REQUIRED — no key, no backup. Generate one with
    // `php artisan backup:keygen` (or `openssl rand -base64 32`) and store it in
    // the secret manager, separate from the backup destination's credentials.
    'encryption_key' => env('BACKUP_ENCRYPTION_KEY'),

    // Include the beneficiary documents disk (PII files) in the archive.
    'documents_disk' => env('BACKUP_DOCUMENTS_DISK', 'local'),
    'include_documents' => (bool) env('BACKUP_INCLUDE_DOCUMENTS', true),

    // Prune offsite artifacts older than this many days after a successful run.
    'retention_days' => (int) env('BACKUP_RETENTION_DAYS', 30),

    // Recovery objectives (minutes) — documented targets, checked by the drill.
    // RPO: max acceptable data loss (drives backup frequency — daily = 1440).
    // RTO: max acceptable time to restore service.
    'rpo_minutes' => (int) env('BACKUP_RPO_MINUTES', 1440),
    'rto_minutes' => (int) env('BACKUP_RTO_MINUTES', 240),

    // pg_dump / psql / pg_restore binaries (production Postgres path). The sqlite
    // path (dev/CI) needs no binary — the database file is copied directly.
    'pg_dump' => env('BACKUP_PG_DUMP_BIN', 'pg_dump'),
    'psql' => env('BACKUP_PSQL_BIN', 'psql'),
];
