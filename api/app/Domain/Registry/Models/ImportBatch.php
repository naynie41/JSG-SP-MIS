<?php

declare(strict_types=1);

namespace App\Domain\Registry\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\ImportStatus;
use App\Domain\Registry\Enums\RegistrationSource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * A bulk import batch (PRD FR-REG-02/06). Owned by, and MDA-scoped to, the
 * uploading MDA; the lifecycle status transitions are audited. Volatile counters
 * are excluded from the audit to avoid noise.
 *
 * @property string $id
 * @property string $owner_mda_id
 * @property string|null $uploaded_by
 * @property string $original_filename
 * @property string $stored_path
 * @property RegistrationSource $source
 * @property string|null $activity_id
 * @property string|null $programme_id
 * @property-read Activity|null $activity
 * @property-read Programme|null $programme
 * @property array<string, mixed>|null $draft_activity
 * @property list<string>|null $detected_headers
 * @property array<string, string|null>|null $column_map
 * @property string|null $source_signature
 * @property Carbon|null $mapping_confirmed_at
 * @property string|null $mapping_confirmed_by
 * @property string|null $mapping_template_id
 * @property string|null $mapping_prefilled_from_id
 * @property-read ImportMappingTemplate|null $mappingTemplate
 * @property-read ImportBatch|null $mappingPrefilledFrom
 * @property-read User|null $mappingConfirmedBy
 * @property ImportStatus $status
 * @property int $total_rows
 * @property int $valid_rows
 * @property int $invalid_rows
 * @property int $rejected_rows
 * @property int $dropped_field_rows
 * @property int $committed_rows
 * @property int $served_rows
 * @property int $own_rows
 * @property int $skipped_rows
 * @property string|null $error
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Mda|null $ownerMda
 * @property-read User|null $uploadedBy
 * @property-read Collection<int, ImportRow> $rows
 */
class ImportBatch extends Model implements MdaScoped
{
    use Auditable, HasUuids, ScopedToMda;

    protected $table = 'import_batches';

    /**
     * Whether a human has confirmed which source column is which canonical field
     * (CLAUDE.md §11). Nothing may be parsed, screened or committed until this is true.
     */
    public function mappingIsConfirmed(): bool
    {
        return $this->mapping_confirmed_at !== null;
    }

    /**
     * Whether the batch is waiting on the queue (nothing for a human to do yet).
     */
    public function isProcessing(): bool
    {
        return in_array($this->status, [ImportStatus::Pending, ImportStatus::Processing, ImportStatus::Committing], true);
    }

    /**
     * Seconds this batch has been waiting on the queue, or null when it is not waiting.
     *
     * Measured from `updated_at` — the last sign of life. A live worker at minimum flips
     * pending → processing, which touches the row; a batch whose timestamp is frozen is
     * one nothing has looked at.
     */
    public function processingForSeconds(): ?int
    {
        if (! $this->isProcessing()) {
            return null;
        }

        // Carbon 3 returns a float from diffInSeconds; seconds-of-waiting is an integer.
        return max(0, (int) (($this->updated_at ?? $this->created_at)?->diffInSeconds(now(), absolute: true) ?? 0));
    }

    /**
     * Waiting on the queue for longer than parsing could plausibly take.
     *
     * Almost always means no queue worker is consuming — the failure mode that has no
     * error anywhere, because nothing failed: the job was never picked up. Computed
     * SERVER-side because only the server's clock can be trusted for this.
     */
    public function processingLooksStalled(): bool
    {
        $seconds = $this->processingForSeconds();

        return $seconds !== null && $seconds >= (int) config('registry.import.stalled_after_seconds', 90);
    }

    /**
     * @var list<string>
     */
    protected $fillable = [
        'owner_mda_id',
        'uploaded_by',
        'original_filename',
        'stored_path',
        'source',
        'activity_id',
        'programme_id',
        'draft_activity',
        'detected_headers',
        'column_map',
        'source_signature',
        'mapping_confirmed_at',
        'mapping_confirmed_by',
        'mapping_template_id',
        'mapping_prefilled_from_id',
        'status',
        'total_rows',
        'valid_rows',
        'invalid_rows',
        'rejected_rows',
        'dropped_field_rows',
        'committed_rows',
        'served_rows',
        'own_rows',
        'skipped_rows',
        'error',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'source' => RegistrationSource::class,
            'status' => ImportStatus::class,
            'draft_activity' => 'array',
            'detected_headers' => 'array',
            'column_map' => 'array',
            'mapping_confirmed_at' => 'datetime',
            'total_rows' => 'integer',
            'valid_rows' => 'integer',
            'invalid_rows' => 'integer',
            'rejected_rows' => 'integer',
            'dropped_field_rows' => 'integer',
            'committed_rows' => 'integer',
            'served_rows' => 'integer',
            'own_rows' => 'integer',
            'skipped_rows' => 'integer',
        ];
    }

    /**
     * Volatile counters produce no audit noise; status transitions still do.
     *
     * @return list<string>
     */
    protected function auditExcluded(): array
    {
        return ['total_rows', 'valid_rows', 'invalid_rows', 'committed_rows', 'served_rows', 'own_rows', 'skipped_rows'];
    }

    /**
     * @return BelongsTo<Mda, $this>
     */
    public function ownerMda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'owner_mda_id');
    }

    /**
     * The registered activity this upload is bound to (PRD §9, FR-REG-10). The
     * resulting intervention (enrollment) is recorded under it.
     *
     * @return BelongsTo<Activity, $this>
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    /**
     * The catalog programme this upload is for.
     *
     * Set when the batch names a programme WITHOUT an activity. When an activity is bound,
     * the programme comes from it instead, so the two cannot disagree — read the effective
     * one via {@see effectiveProgrammeId()}.
     *
     * @return BelongsTo<Programme, $this>
     */
    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }

    /**
     * The programme this batch enrolls into: the bound activity's, else its own.
     *
     * Short-circuits on `activity_id` rather than loading the relation and testing it,
     * because {@see Activity} is soft-deleted — an archived activity makes the relation
     * null while the id is still there, and falling back to `programme_id` in that case
     * is right: the batch's own record of its programme outlives the activity row.
     */
    public function effectiveProgrammeId(): ?string
    {
        if ($this->activity_id === null) {
            return $this->programme_id;
        }

        /** @var Activity|null $activity */
        $activity = $this->activity;

        return $activity instanceof Activity ? $activity->programme_id : $this->programme_id;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function mappingConfirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mapping_confirmed_by');
    }

    /**
     * The saved mapping this batch used, when one applied (CLAUDE.md §11). Null for a
     * file shape seen for the first time — and the batch's own `column_map` is the
     * record of what was actually applied either way.
     *
     * @return BelongsTo<ImportMappingTemplate, $this>
     */
    public function mappingTemplate(): BelongsTo
    {
        return $this->belongsTo(ImportMappingTemplate::class, 'mapping_template_id');
    }

    /**
     * The EARLIER BATCH this batch's mapping was pre-filled from.
     *
     * Set when the same MDA uploads the same file shape again without ever having saved
     * a named template — the ordinary case. Distinct from `mappingTemplate`: a template
     * is a deliberate, reusable artefact, this is "we recognised the layout from your
     * last import". Either way the mapping is only pre-filled, never pre-confirmed.
     *
     * @return BelongsTo<ImportBatch, $this>
     */
    public function mappingPrefilledFrom(): BelongsTo
    {
        return $this->belongsTo(ImportBatch::class, 'mapping_prefilled_from_id');
    }

    /**
     * @return HasMany<ImportRow, $this>
     */
    public function rows(): HasMany
    {
        return $this->hasMany(ImportRow::class);
    }
}
