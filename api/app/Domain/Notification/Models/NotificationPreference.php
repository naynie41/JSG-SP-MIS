<?php

declare(strict_types=1);

namespace App\Domain\Notification\Models;

use App\Domain\Access\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A user's notification channel preferences (PRD FR-NOT-02). Per-user (no MDA
 * scope). In-app is always delivered; these toggles gate outbound channels.
 *
 * @property string $id
 * @property string $user_id
 * @property bool $email_enabled
 * @property-read User $user
 */
class NotificationPreference extends Model
{
    use HasUuids;

    protected $table = 'notification_preferences';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'email_enabled',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_enabled' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
