<?php

declare(strict_types=1);

namespace App\Domain\Registry\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Programme\Models\Activity;
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
 * @property string $activity_id
 * @property ImportStatus $status
 * @property int $total_rows
 * @property int $valid_rows
 * @property int $invalid_rows
 * @property int $rejected_rows
 * @property int $dropped_field_rows
 * @property int $committed_rows
 * @property int $served_rows
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
     * @var list<string>
     */
    protected $fillable = [
        'owner_mda_id',
        'uploaded_by',
        'original_filename',
        'stored_path',
        'source',
        'activity_id',
        'status',
        'total_rows',
        'valid_rows',
        'invalid_rows',
        'rejected_rows',
        'dropped_field_rows',
        'committed_rows',
        'served_rows',
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
            'total_rows' => 'integer',
            'valid_rows' => 'integer',
            'invalid_rows' => 'integer',
            'rejected_rows' => 'integer',
            'dropped_field_rows' => 'integer',
            'committed_rows' => 'integer',
            'served_rows' => 'integer',
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
        return ['total_rows', 'valid_rows', 'invalid_rows', 'committed_rows', 'served_rows', 'skipped_rows'];
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
     * @return BelongsTo<User, $this>
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * @return HasMany<ImportRow, $this>
     */
    public function rows(): HasMany
    {
        return $this->hasMany(ImportRow::class);
    }
}
