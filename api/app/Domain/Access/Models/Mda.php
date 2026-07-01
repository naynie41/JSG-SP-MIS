<?php

declare(strict_types=1);

namespace App\Domain\Access\Models;

use App\Domain\Access\Enums\MdaStatus;
use App\Domain\Access\Enums\MdaType;
use App\Domain\Audit\Concerns\Auditable;
use Database\Factories\MdaFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A Ministry, Department or Agency. Owns its users and (later) the beneficiary
 * records it originates.
 */
class Mda extends Model
{
    /** @use HasFactory<MdaFactory> */
    use Auditable, HasFactory, HasUuids, SoftDeletes;

    protected $table = 'mdas';

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
