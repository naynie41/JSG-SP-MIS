<?php

declare(strict_types=1);

namespace App\Domain\Sync\Models;

use App\Domain\Access\Models\Mda;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Sync\Enums\ConflictPolicy;
use Database\Factories\SyncConnectorFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A configured external/source system to synchronize from (FR-DSH-02): a SOCU or
 * government-system feed, owned by an MDA, with a conflict policy and a schedule.
 * Credentials are NOT stored here — `credentials_ref` keys into config/env.
 *
 * @property string $id
 * @property string $name
 * @property RegistrationSource $source
 * @property string $owner_mda_id
 * @property ConflictPolicy $conflict_policy
 * @property string|null $credentials_ref
 * @property bool $enabled
 * @property string|null $schedule
 * @property Carbon|null $last_run_at
 */
class SyncConnector extends Model
{
    /** @use HasFactory<SyncConnectorFactory> */
    use HasFactory, HasUuids;

    protected $table = 'sync_connectors';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'source',
        'owner_mda_id',
        'conflict_policy',
        'credentials_ref',
        'enabled',
        'schedule',
        'last_run_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'source' => RegistrationSource::class,
            'conflict_policy' => ConflictPolicy::class,
            'enabled' => 'boolean',
            'last_run_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Mda, $this>
     */
    public function ownerMda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'owner_mda_id');
    }

    protected static function newFactory(): SyncConnectorFactory
    {
        return SyncConnectorFactory::new();
    }
}
