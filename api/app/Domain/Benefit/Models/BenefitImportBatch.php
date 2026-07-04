<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\Mda;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Enums\ImportStatus;
use Database\Factories\BenefitImportBatchFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * A bulk benefit-delivery import batch (PRD FR-BEN-01/02, §8.3): a distribution
 * list keyed to an activity. Scoped to (and owned by) the importing = delivering
 * MDA. Reuses the Phase 2 import lifecycle.
 *
 * @property string $id
 * @property string $mda_id
 * @property string $activity_id
 * @property string $programme_id
 * @property string|null $uploaded_by
 * @property string $original_filename
 * @property string $stored_path
 * @property string $source
 * @property ImportStatus $status
 * @property int $total_rows
 * @property int $valid_rows
 * @property int $invalid_rows
 * @property int $committed_rows
 * @property string|null $error
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Activity $activity
 * @property-read Programme $programme
 * @property-read Mda $mda
 * @property-read Collection<int, BenefitImportRow> $rows
 */
class BenefitImportBatch extends Model implements MdaScoped
{
    /** @use HasFactory<BenefitImportBatchFactory> */
    use Auditable, HasFactory, HasUuids, ScopedToMda;

    protected $table = 'benefit_import_batches';

    public function mdaOwnershipColumn(): string
    {
        return 'mda_id';
    }

    /** Volatile progress counters shouldn't generate audit noise. */
    protected function auditExcluded(): array
    {
        return ['total_rows', 'valid_rows', 'invalid_rows', 'committed_rows'];
    }

    /**
     * @var list<string>
     */
    protected $fillable = [
        'mda_id',
        'activity_id',
        'programme_id',
        'uploaded_by',
        'original_filename',
        'stored_path',
        'source',
        'status',
        'total_rows',
        'valid_rows',
        'invalid_rows',
        'committed_rows',
        'error',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ImportStatus::class,
            'total_rows' => 'integer',
            'valid_rows' => 'integer',
            'invalid_rows' => 'integer',
            'committed_rows' => 'integer',
        ];
    }

    protected static function newFactory(): BenefitImportBatchFactory
    {
        return BenefitImportBatchFactory::new();
    }

    /**
     * @return BelongsTo<Activity, $this>
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * @return BelongsTo<Programme, $this>
     */
    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    /**
     * @return BelongsTo<Mda, $this>
     */
    public function mda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'mda_id');
    }

    /**
     * @return HasMany<BenefitImportRow, $this>
     */
    public function rows(): HasMany
    {
        return $this->hasMany(BenefitImportRow::class);
    }
}
