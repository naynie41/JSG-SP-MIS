<?php

declare(strict_types=1);

namespace App\Domain\Sync\Models;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Programme\Models\Activity;
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
 * @property string|null $activity_id
 * @property ConflictPolicy $conflict_policy
 * @property string|null $credentials_ref
 * @property bool $enabled
 * @property string|null $schedule
 * @property Carbon|null $last_run_at
 * @property array<string, string|null>|null $column_map
 * @property string|null $source_signature
 * @property Carbon|null $mapping_confirmed_at
 * @property string|null $mapping_confirmed_by
 * @property Carbon|null $mapping_stale_at
 * @property string|null $mapping_stale_reason
 * @property-read User|null $mappingConfirmedBy
 * @property-read Activity|null $activity
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
        'activity_id',
        'conflict_policy',
        'credentials_ref',
        'enabled',
        'schedule',
        'last_run_at',
        'column_map',
        'source_signature',
        'mapping_confirmed_at',
        'mapping_confirmed_by',
        'mapping_stale_at',
        'mapping_stale_reason',
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
            'column_map' => 'array',
            'mapping_confirmed_at' => 'datetime',
            'mapping_stale_at' => 'datetime',
        ];
    }

    /**
     * The connector's mapping state, as an administrator needs to see it.
     *
     * `stale` is the one that matters: a standing approval that no longer describes what
     * the source is sending. It is a distinct state from "never configured" because the
     * remedy differs — one needs a first mapping, the other needs a REVIEW of a mapping
     * that used to be right.
     */
    public function mappingStatus(): string
    {
        return match (true) {
            ! $this->mappingIsConfirmed() => 'never_configured',
            $this->mapping_stale_at !== null => 'stale',
            default => 'confirmed',
        };
    }

    /** Whether the source's shape has moved since the mapping was approved. */
    public function mappingIsStale(): bool
    {
        return $this->mapping_stale_at !== null;
    }

    /**
     * Whether a person has approved which source field holds each identity value
     * (CLAUDE.md §11). A connector runs unattended, so this confirmation is given once at
     * configuration time and STANDS for later runs — but a run whose records no longer
     * match {@see $source_signature} must stop and ask again.
     */
    public function mappingIsConfirmed(): bool
    {
        return $this->mapping_confirmed_at !== null && $this->column_map !== null;
    }

    /**
     * @return BelongsTo<Mda, $this>
     */
    public function ownerMda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'owner_mda_id');
    }

    /**
     * Who gave the standing mapping approval. Shown in the UI because a standing
     * approval is accountable to a person, not just a timestamp.
     *
     * @return BelongsTo<User, $this>
     */
    public function mappingConfirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mapping_confirmed_by');
    }

    /**
     * The activity every synced row binds to (activity-first).
     *
     * The connector's equivalent of the activity an officer picks when uploading a file.
     * Unscoped, because the engine runs on the queue with no authenticated user to
     * resolve a scope from; the activity is constrained to the connector's own MDA at
     * configuration time instead, which is where the decision belongs.
     *
     * @return BelongsTo<Activity, $this>
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id')->withoutGlobalScope(MdaScope::class);
    }

    /**
     * Why this connector may not sync yet, or null when it may.
     *
     * Activity-first is not advisory here: without an activity there is nothing to
     * attribute a synced delivery to, so the run is HELD rather than allowed to put
     * people into the register unattached. The activity's creator is who the engine
     * enrols as, so an activity whose creator has since been removed holds too — it can
     * no longer answer "who did this", and recording an intervention with no actor would
     * lose exactly the accountability the binding exists to keep.
     */
    public function activityBindingBlocker(): ?string
    {
        if ($this->activity_id === null) {
            return 'This connector has no target activity. Synced records bind to an activity exactly '
                .'as an uploaded file does, so set one on the connector before it can run.';
        }

        $activity = $this->activity()->first();
        if ($activity === null) {
            return 'The target activity for this connector no longer exists. Point it at a current one.';
        }
        if ($activity->created_by === null) {
            return 'The target activity has no recorded creator, so a synced delivery could not be '
                .'attributed to anyone. Point this connector at a current activity.';
        }

        return null;
    }

    protected static function newFactory(): SyncConnectorFactory
    {
        return SyncConnectorFactory::new();
    }
}
