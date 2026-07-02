<?php

declare(strict_types=1);

namespace App\Domain\Access\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Enums\MdaStatus;
use App\Domain\Access\Enums\MdaType;
use App\Domain\Audit\Concerns\Auditable;
use Database\Factories\MdaFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * A Ministry, Department or Agency. Owns its users and (later) the beneficiary
 * records it originates.
 *
 * @property string $id
 * @property string $name
 * @property MdaType $type
 * @property MdaStatus $status
 * @property string|null $contact_person
 * @property string|null $contact_email
 * @property string|null $contact_phone
 * @property string|null $address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Mda extends Model implements MdaScoped
{
    /** @use HasFactory<MdaFactory> */
    use Auditable, HasFactory, HasUuids, ScopedToMda, SoftDeletes;

    protected $table = 'mdas';

    /**
     * An MDA is scoped on its own primary key: a user sees their own MDA (and
     * any granted MDAs), unless they hold cross-mda.view (FR-UAM-03).
     */
    public function mdaOwnershipColumn(): string
    {
        return 'id';
    }

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'type',
        'contact_person',
        'contact_email',
        'contact_phone',
        'address',
        'status',
    ];

    /**
     * Model-level default mirroring the database default.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => MdaStatus::Active->value,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => MdaType::class,
            'status' => MdaStatus::class,
        ];
    }

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Cross-MDA grants that give other users access to this MDA.
     *
     * @return HasMany<MdaAccessGrant, $this>
     */
    public function accessGrants(): HasMany
    {
        return $this->hasMany(MdaAccessGrant::class);
    }

    protected static function newFactory(): MdaFactory
    {
        return MdaFactory::new();
    }
}
