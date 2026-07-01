<?php

declare(strict_types=1);

namespace App\Domain\Audit\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

/**
 * An immutable audit log entry (FR-AUD-01). Append-only: the model refuses to be
 * updated or deleted in application code, and the database enforces the same with
 * triggers (NFR-AUD-01). Never make this model Auditable (no recursion).
 */
class AuditLog extends Model
{
    use HasUuids;

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
     * triggers; also enforced on sqlite used in tests).
     */
    protected static function booted(): void
    {
        static::updating(function (): void {
            throw new RuntimeException('audit_log is append-only and cannot be updated.');
        });

        static::deleting(function (): void {
            throw new RuntimeException('audit_log is append-only and cannot be deleted.');
        });
    }
}
