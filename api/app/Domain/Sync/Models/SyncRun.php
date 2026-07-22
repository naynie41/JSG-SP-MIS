<?php

declare(strict_types=1);

namespace App\Domain\Sync\Models;

use App\Domain\Sync\Enums\ConflictPolicy;
use App\Domain\Sync\Enums\SyncStatus;
use App\Domain\Sync\Enums\SyncTrigger;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * One execution of synchronization (FR-DSH-02, FR-REG-08) — scheduled, manually
 * triggered, or an offline batch. Carries the running tallies surfaced to admins.
 *
 * @property string $id
 * @property string|null $connector_id
 * @property SyncTrigger $trigger
 * @property string $source
 * @property string $owner_mda_id
 * @property ConflictPolicy $conflict_policy
 * @property SyncStatus $status
 * @property int $fetched_count
 * @property int $created_count
 * @property int $updated_count
 * @property int $skipped_count
 * @property int $flagged_count
 * @property int $rejected_count
 * @property int $error_count
 * @property string|null $error
 * @property string|null $triggered_by
 * @property Carbon|null $started_at
 * @property Carbon|null $finished_at
 */
class SyncRun extends Model
{
    use HasUuids;

    protected $table = 'sync_runs';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'connector_id', 'trigger', 'source', 'owner_mda_id', 'conflict_policy', 'status',
        'fetched_count', 'created_count', 'updated_count', 'skipped_count', 'flagged_count',
        'rejected_count', 'error_count', 'error', 'triggered_by', 'started_at', 'finished_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'trigger' => SyncTrigger::class,
            'conflict_policy' => ConflictPolicy::class,
            'status' => SyncStatus::class,
            'fetched_count' => 'integer',
            'created_count' => 'integer',
            'updated_count' => 'integer',
            'skipped_count' => 'integer',
            'flagged_count' => 'integer',
            'rejected_count' => 'integer',
            'error_count' => 'integer',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<SyncConnector, $this>
     */
    public function connector(): BelongsTo
    {
        return $this->belongsTo(SyncConnector::class);
    }

    /**
     * @return HasMany<SyncRunRow, $this>
     */
    public function rows(): HasMany
    {
        return $this->hasMany(SyncRunRow::class);
    }
}
