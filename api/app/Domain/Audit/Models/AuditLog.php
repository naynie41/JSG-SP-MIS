<?php

declare(strict_types=1);

namespace App\Domain\Audit\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * An immutable audit log entry (FR-AUD-01). Append-only: the model refuses to be
 * updated or deleted in application code, and the database enforces the same with
 * triggers (NFR-AUD-01). Never make this model Auditable (no recursion).
 *
 * Tamper-evidence is layered (Phase 7 hardening):
 *  1. no UPDATE/DELETE/TRUNCATE — app guard + PostgreSQL triggers;
 *  2. hash chain — every entry stores `chain_position`, the previous entry's
 *     `entry_hash`, and a SHA-256 over its own canonical payload + that hash, so
 *     any edit/removal/reorder breaks every later hash. Verified offline with
 *     `php artisan audit:verify-chain`. Rows older than the chain migration have
 *     a NULL position (pre-chain era; the log itself was never mutable).
 */
class AuditLog extends Model
{
    use HasUuids;

    public const GENESIS_HASH = '0000000000000000000000000000000000000000000000000000000000000000';

    public $timestamps = false;

    protected $table = 'audit_log';

    protected $fillable = [
        'actor_id',
        'actor_mda_id',
        'action',
        'entity_type',
        'entity_id',
        'before',
        'after',
        'ip_address',
        'user_agent',
        'correlation_id',
        'created_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'before' => 'array',
            'after' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Application-level append-only guard (defence in depth alongside the DB
     * triggers; also enforced on sqlite used in tests). The creating hook links
     * each new entry into the hash chain.
     */
    protected static function booted(): void
    {
        static::creating(function (AuditLog $entry): void {
            // Pin the timestamp app-side: the hash covers created_at, so it must
            // never be left to a DB default the hash can't see.
            $entry->created_at ??= now();

            $head = static::query()
                ->whereNotNull('chain_position')
                ->orderByDesc('chain_position')
                ->first();

            $entry->chain_position = (int) ($head?->chain_position ?? 0) + 1;
            $entry->prev_hash = $head?->entry_hash ?? self::GENESIS_HASH;
            $entry->entry_hash = $entry->computeEntryHash();
        });

        static::updating(function (): void {
            throw new RuntimeException('audit_log is append-only and cannot be updated.');
        });

        static::deleting(function (): void {
            throw new RuntimeException('audit_log is append-only and cannot be deleted.');
        });
    }

    /**
     * Insert with a small retry: concurrent writers may race for the same chain
     * position; the partial-unique index rejects the loser, which simply re-reads
     * the new head and links again. Never retried for any other failure.
     *
     * @param  array<string, mixed>  $options
     */
    public function save(array $options = []): bool
    {
        if ($this->exists) {
            return parent::save($options); // updates are rejected by the guard above
        }

        $attempts = 0;
        while (true) {
            try {
                return DB::transaction(fn (): bool => parent::save($options));
            } catch (QueryException $e) {
                $isChainRace = str_contains($e->getMessage(), 'chain_position');
                if (! $isChainRace || ++$attempts >= 3) {
                    throw $e;
                }
            }
        }
    }

    /**
     * The entry's tamper-evident hash: SHA-256 over the canonical JSON of every
     * persisted field plus the previous entry's hash. Deterministic across
     * drivers (sorted keys, fixed timestamp format), so a verifier can recompute
     * it from the stored row alone.
     */
    public function computeEntryHash(): string
    {
        $payload = [
            'chain_position' => (int) $this->chain_position,
            'prev_hash' => (string) $this->prev_hash,
            'actor_id' => $this->actor_id,
            'actor_mda_id' => $this->actor_mda_id,
            'action' => $this->action,
            'entity_type' => $this->entity_type,
            'entity_id' => $this->entity_id === null ? null : (string) $this->entity_id,
            'before' => self::canonicalize($this->before),
            'after' => self::canonicalize($this->after),
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'correlation_id' => $this->correlation_id,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];

        return hash('sha256', (string) json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Recursively key-sort an array so hashing is independent of insertion order.
     *
     * @param  array<array-key, mixed>|null  $value
     * @return array<array-key, mixed>|null
     */
    private static function canonicalize(?array $value): ?array
    {
        if ($value === null) {
            return null;
        }

        ksort($value);
        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $value[$key] = self::canonicalize($item);
            }
        }

        return $value;
    }
}
