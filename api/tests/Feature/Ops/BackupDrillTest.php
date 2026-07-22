<?php

declare(strict_types=1);

namespace Tests\Feature\Ops;

use App\Domain\Ops\Backup\BackupCipher;
use App\Domain\Ops\Backup\BackupService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Tests\TestCase;

/**
 * Backup + restore drill (NFR-AVAIL-01): a backup is ENCRYPTED (never plaintext),
 * ships to the offsite disk, and can be restored + verified within the RTO. Runs
 * against a throwaway file-based sqlite database so the full encrypt → offsite →
 * decrypt → restore → verify cycle is exercised end-to-end.
 */
class BackupDrillTest extends TestCase
{
    use RefreshDatabase;

    private string $sourceFile;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('offsite');
        Storage::fake('docs');

        config([
            'backup.disk' => 'offsite',
            'backup.path' => 'backups',
            'backup.encryption_key' => 'test-backup-key-'.Str::random(16),
            'backup.documents_disk' => 'docs',
            'backup.include_documents' => false,
            'backup.retention_days' => 30,
            'backup.rto_minutes' => 240,
        ]);
        // The cipher is a singleton bound from config — rebind against the test key.
        $this->app->forgetInstance(BackupCipher::class);

        // A real, file-backed source database with known content.
        $this->sourceFile = sys_get_temp_dir().'/spmis-backup-src-'.Str::uuid()->toString().'.sqlite';
        touch($this->sourceFile);
        config(['database.connections.backup_src' => [
            'driver' => 'sqlite', 'database' => $this->sourceFile, 'prefix' => '', 'foreign_key_constraints' => false,
        ]]);
        DB::connection('backup_src')->statement('CREATE TABLE widgets (id INTEGER PRIMARY KEY, secret TEXT)');
        DB::connection('backup_src')->table('widgets')->insert([
            ['id' => 1, 'secret' => 'TOP-SECRET-MARKER-ABC'],
            ['id' => 2, 'secret' => 'another-row'],
        ]);
    }

    protected function tearDown(): void
    {
        DB::purge('backup_src');
        @unlink($this->sourceFile);
        parent::tearDown();
    }

    private function service(): BackupService
    {
        return $this->app->make(BackupService::class);
    }

    public function test_backup_is_written_encrypted_to_the_offsite_disk(): void
    {
        $result = $this->service()->run('backup_src');

        Storage::disk('offsite')->assertExists($result->path);
        $this->assertStringEndsWith('.zip.enc', $result->path);

        // The artifact must be ciphertext — the known plaintext marker must NOT appear.
        $bytes = Storage::disk('offsite')->get($result->path);
        $this->assertStringNotContainsString('TOP-SECRET-MARKER-ABC', $bytes);
        $this->assertGreaterThan(0, $result->sizeBytes);
    }

    public function test_a_backup_is_refused_without_an_encryption_key(): void
    {
        config(['backup.encryption_key' => null]);
        $this->app->forgetInstance(BackupCipher::class);

        $this->expectException(RuntimeException::class);
        $this->service()->run('backup_src');
    }

    public function test_restore_drill_recovers_and_verifies_within_the_rto(): void
    {
        $drill = $this->service()->drill('backup_src');

        $this->assertTrue($drill->passed, 'restore drill should pass');
        $this->assertTrue($drill->countsMatch, 'restored row counts should match the source');
        $this->assertTrue($drill->withinRto, 'the drill should complete within the RTO');
        $this->assertSame(['widgets' => 2], $drill->restoredCounts);
    }

    public function test_a_tampered_artifact_fails_integrity_on_restore(): void
    {
        $result = $this->service()->run('backup_src');

        // Corrupt one byte of the ciphertext body (past the 32-byte MAC).
        $bytes = Storage::disk('offsite')->get($result->path);
        $bytes[80] = $bytes[80] === 'A' ? 'B' : 'A';
        Storage::disk('offsite')->put($result->path, $bytes);

        config(['database.connections.tamper_restore' => [
            'driver' => 'sqlite', 'database' => sys_get_temp_dir().'/spmis-tamper-'.Str::uuid()->toString().'.sqlite',
            'prefix' => '', 'foreign_key_constraints' => false,
        ]]);
        touch((string) config('database.connections.tamper_restore.database'));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageMatches('/integrity check failed/i');
        $this->service()->restoreDatabaseInto($result->path, 'tamper_restore');
    }

    public function test_documents_are_included_and_restorable(): void
    {
        config(['backup.include_documents' => true]);
        Storage::disk('docs')->put('documents/b-1/id-card.pdf', 'DOC-CONTENT');

        $result = $this->service()->run('backup_src');
        $this->assertTrue($result->includedDocuments);

        // Restore into a scratch DB + a fresh documents disk; the file comes back.
        Storage::fake('docs_restored');
        config(['database.connections.doc_restore' => [
            'driver' => 'sqlite', 'database' => sys_get_temp_dir().'/spmis-docr-'.Str::uuid()->toString().'.sqlite',
            'prefix' => '', 'foreign_key_constraints' => false,
        ]]);
        touch((string) config('database.connections.doc_restore.database'));

        $this->service()->restoreDatabaseInto($result->path, 'doc_restore', 'docs_restored');
        Storage::disk('docs_restored')->assertExists('documents/b-1/id-card.pdf');
        $this->assertSame('DOC-CONTENT', Storage::disk('docs_restored')->get('documents/b-1/id-card.pdf'));
    }

    public function test_cipher_round_trips_and_detects_tampering(): void
    {
        $cipher = new BackupCipher('a-key');
        $envelope = $cipher->encrypt('hello world');
        $this->assertSame('hello world', $cipher->decrypt($envelope));

        // Wrong key → integrity failure, never silent garbage.
        $this->expectException(RuntimeException::class);
        (new BackupCipher('different-key'))->decrypt($envelope);
    }
}
