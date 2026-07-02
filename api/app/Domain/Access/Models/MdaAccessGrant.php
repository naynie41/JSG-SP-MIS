<?php

declare(strict_types=1);

namespace App\Domain\Access\Models;

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
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
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
}
