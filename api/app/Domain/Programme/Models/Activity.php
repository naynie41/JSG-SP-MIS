<?php

declare(strict_types=1);

namespace App\Domain\Programme\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Programme\Enums\ActivityStatus;
use Database\Factories\ActivityFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * A unit of work under a programme (PRD FR-PRG-02). `owner_mda_id` is denormalised
 * from the parent programme so the shared MdaScope applies directly — an activity
 * is scoped to (and mutable only by) the programme's owner MDA. Auditable; budget
 * is integer minor units (kobo, NGN). A PostGIS `geom` column exists on PostgreSQL
 * for later GIS work (not surfaced yet).
 *
 * @property string $id
 * @property string $programme_id
 * @property string $owner_mda_id
 * @property string $name
 * @property string|null $description
 * @property int|null $target_count
 * @property string|null $lga
 * @property string|null $ward
 * @property string|null $location_description
 * @property array<string, mixed>|null $schedule
 * @property Carbon|null $starts_on
 * @property Carbon|null $ends_on
 * @property int|null $budget_amount
 * @property ActivityStatus $status
 * @property string|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Programme $programme
 * @property-read Mda $ownerMda
 */
class Activity extends Model implements MdaScoped
{
    /** @use HasFactory<ActivityFactory> */
    use Auditable, HasFactory, HasUuids, ScopedToMda, SoftDeletes;

    protected $table = 'activities';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'programme_id',
        'owner_mda_id',
        'name',
        'description',
        'target_count',
        'lga',
        'ward',
        'location_description',
        'schedule',
        'starts_on',
        'ends_on',
        'budget_amount',
        'status',
        'created_by',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => ActivityStatus::Draft->value,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ActivityStatus::class,
            'schedule' => 'array',
            'starts_on' => 'date',
            'ends_on' => 'date',
            'target_count' => 'integer',
            'budget_amount' => 'integer',
        ];
    }

    protected static function newFactory(): ActivityFactory
    {
        return ActivityFactory::new();
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
    public function ownerMda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'owner_mda_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
