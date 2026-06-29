<?php

declare(strict_types=1);

namespace App\Domain\Access\Models;

use App\Domain\Access\Enums\UserStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, HasUuids, Notifiable, SoftDeletes;

    /**
     * Mass-assignable attributes. The MFA secret and lockout counters are
     * intentionally excluded — they are managed by application logic, never
     * by mass assignment (SECURITY.md).
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mda_id',
        'status',
        'mfa_enabled',
    ];

    /**
     * Model-level defaults mirroring the database defaults so freshly
     * instantiated models match what is persisted.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => UserStatus::Active->value,
        'mfa_enabled' => false,
        'failed_login_attempts' => 0,
    ];

    /**
     * Attributes hidden from serialization. The MFA secret is never exposed.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'mfa_secret',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
            'mfa_secret' => 'encrypted', // encrypted at rest (SECURITY.md §2)
            'mfa_enabled' => 'boolean',
            'failed_login_attempts' => 'integer',
            'locked_until' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * The MDA this user belongs to (nullable for non-MDA-bound roles).
     *
     * @return BelongsTo<Mda, $this>
     */
    public function mda(): BelongsTo
    {
        return $this->belongsTo(Mda::class);
    }

    /**
     * Explicit grants giving this user access to MDAs other than their own.
     *
     * @return HasMany<MdaAccessGrant, $this>
     */
    public function mdaAccessGrants(): HasMany
    {
        return $this->hasMany(MdaAccessGrant::class);
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
