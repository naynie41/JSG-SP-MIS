<?php

declare(strict_types=1);

namespace App\Domain\Access\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * An explicit grant allowing a user to access an MDA other than their own
 * (PRD FR-UAM-03, FR-DSH-01). Consumed by the central MDA data-scoping logic.
 *
 * @property string $id
 * @property string $user_id
 * @property string $mda_id
 * @property string|null $granted_by
 * @property string|null $reason
 * @property Carbon|null $expires_at
 * @property Carbon|null $revoked_at
 * @property string|null $revoked_by
 * @property string|null $revocation_reason
 * @property Carbon|null $created_at
 */
class MdaAccessGrant extends Model
{
    use HasUuids;

    protected $table = 'mda_access_grants';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'mda_id',
        'granted_by',
        'reason',
        'expires_at',
        'revoked_at',
        'revoked_by',
        'revocation_reason',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    /**
     * Whether this grant currently opens access.
     *
     * Two ways it can be over, and they are not the same fact: it EXPIRED on a date set
     * when it was issued, or someone REVOKED it. Both are kept, because an audit of who
     * held access to citizen records has to be able to tell a lapse from a withdrawal.
     */
    public function isActive(): bool
    {
        return $this->revoked_at === null
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Constrain a query to grants that currently open access.
     *
     * @param  Builder<covariant MdaAccessGrant>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->whereNull('revoked_at')
            ->where(function (Builder $inner): void {
                $inner->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Mda, $this>
     */
    public function mda(): BelongsTo
    {
        return $this->belongsTo(Mda::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function grantedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    /**
     * Who withdrew the access. Kept on the row rather than only in the audit log so the
     * grant itself answers "when did this end, and on whose authority".
     *
     * @return BelongsTo<User, $this>
     */
    public function revokedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }
}
