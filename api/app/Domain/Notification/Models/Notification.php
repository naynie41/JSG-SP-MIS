<?php

declare(strict_types=1);

namespace App\Domain\Notification\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * An in-app notification addressed to one recipient user (PRD FR-NOT-01). MDA-scoped
 * on `recipient_mda_id` (the recipient's MDA at send time) for cross-MDA isolation,
 * and always queried per-user (recipient_user_id) by the controller. Not Auditable —
 * notifications are derived, high-volume records.
 *
 * @property string $id
 * @property string $recipient_user_id
 * @property string|null $recipient_mda_id
 * @property string $type
 * @property string $subject
 * @property string|null $body
 * @property array<string, mixed>|null $payload
 * @property string|null $related_type
 * @property string|null $related_id
 * @property Carbon|null $read_at
 * @property Carbon|null $created_at
 * @property-read User $recipient
 */
class Notification extends Model implements MdaScoped
{
    use HasUuids, ScopedToMda;

    protected $table = 'notifications';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'recipient_user_id',
        'recipient_mda_id',
        'type',
        'subject',
        'body',
        'payload',
        'related_type',
        'related_id',
        'read_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'read_at' => 'datetime',
        ];
    }

    /** Scoped to the recipient's MDA, not the Phase 2 owner column. */
    public function mdaOwnershipColumn(): string
    {
        return 'recipient_mda_id';
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }
}
