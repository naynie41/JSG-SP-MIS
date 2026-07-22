<?php

declare(strict_types=1);

namespace App\Domain\Ops\Backup;

/**
 * The outcome of a successful backup run (NFR-AVAIL-01): where the encrypted
 * artifact landed and how big / how long it took.
 */
final class BackupResult
{
    public function __construct(
        public readonly string $disk,
        public readonly string $path,
        public readonly int $sizeBytes,
        public readonly float $durationSeconds,
        public readonly bool $includedDocuments,
        public readonly string $createdAt,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'disk' => $this->disk,
            'path' => $this->path,
            'size_bytes' => $this->sizeBytes,
            'duration_seconds' => round($this->durationSeconds, 2),
            'included_documents' => $this->includedDocuments,
            'encrypted' => true,
            'created_at' => $this->createdAt,
        ];
    }
}
