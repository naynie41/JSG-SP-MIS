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
        'role_id',
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
            'mfa_recovery_codes' => 'encrypted:array', // one-time codes, encrypted at rest
            'mfa_enabled' => 'boolean',
            'failed_login_attempts' => 'integer',
            'locked_until' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Whether MFA is mandatory for this user (driven by the role, FR-UAM-04).
     */
    public function mfaRequired(): bool
    {
        return (bool) $this->role?->requires_mfa;
    }

    /**
     * Whether the account is currently locked out (FR-UAM-06).
     */
    public function isLocked(): bool
    {
        return $this->locked_until !== null && $this->locked_until->isFuture();
    }

    /**
     * Record a failed login/MFA attempt and apply lockout with exponential
     * backoff once the configured threshold is reached. Returns true if this
     * attempt caused (or extended) a lock.
     */
    public function registerFailedAttempt(): bool
    {
        $this->increment('failed_login_attempts');

        $max = (int) config('security.lockout.max_attempts');

        if ($this->failed_login_attempts < $max) {
            return false;
        }

        $decay = (int) config('security.lockout.decay_minutes');
        $multiplier = max(1, (int) config('security.lockout.multiplier'));
        $cap = (int) config('security.lockout.max_minutes');

        $exponent = $this->failed_login_attempts - $max; // 0 on the first lock
        $minutes = min($decay * ($multiplier ** $exponent), $cap);

        $this->forceFill(['locked_until' => now()->addMinutes($minutes)])->save();

        return true;
    }

    /**
     * Clear lockout state after a successful authentication.
     */
    public function clearLockout(): void
    {
        if ($this->failed_login_attempts === 0 && $this->locked_until === null) {
            return;
        }

        $this->forceFill([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ])->save();
    }

    /**
     * Consume a one-time recovery code if it matches. Returns true and removes
     * the code on success; false otherwise (constant-time comparison).
     */
    public function consumeRecoveryCode(string $code): bool
    {
        $code = trim($code);
        $codes = $this->mfa_recovery_codes ?? [];

        foreach ($codes as $index => $stored) {
            if (hash_equals((string) $stored, $code)) {
                unset($codes[$index]);
                $this->forceFill(['mfa_recovery_codes' => array_values($codes)])->save();

                return true;
            }
        }

        return false;
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
     * The user's role (single role per user, PRD FR-UAM-02). Permissions are
     * resolved through the role; authorization always checks permissions.
     *
     * @return BelongsTo<Role, $this>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * The permission keys granted to this user via their role.
     *
     * @return list<string>
     */
    public function permissionKeys(): array
    {
        return $this->role?->permissions->pluck('key')->all() ?? [];
    }

    /**
     * Whether the user has a given `module.action` permission.
     */
    public function hasPermission(string $key): bool
    {
        return in_array($key, $this->permissionKeys(), true);
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

    /**
     * Invalidate all access tokens whenever the password changes
     * (SECURITY.md §2: invalidate tokens on password change).
     */
    protected static function booted(): void
    {
        static::updated(function (User $user): void {
            if ($user->wasChanged('password')) {
                $user->tokens()->delete();
            }
        });
    }
}
