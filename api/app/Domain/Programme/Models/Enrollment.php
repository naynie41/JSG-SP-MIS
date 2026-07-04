<?php

declare(strict_types=1);

namespace App\Domain\Programme\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\Mda;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Programme\Enums\EnrollmentStatus;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use Database\Factories\EnrollmentFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * An enrollment of a beneficiary (individual programme) or household (household
 * programme) into a programme/activity (PRD FR-PRG-03).
 *
 * Scoped on `mda_id` — the ENROLLING MDA (the programme owner), which may differ
 * from the beneficiary's owner MDA when a serving MDA enrolls a beneficiary it
 * serves (no ownership change). Auditable.
 *
 * @property string $id
 * @property string $programme_id
 * @property string|null $activity_id
 * @property string $mda_id
 * @property string|null $beneficiary_id
 * @property string|null $household_id
 * @property EnrollmentStatus $status
 * @property Carbon $enrolled_on
 * @property Carbon|null $exited_on
 * @property string|null $exit_reason
 * @property bool $eligibility_flagged
 * @property array<int, string>|null $eligibility_notes
 * @property string|null $enrolled_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Programme $programme
 * @property-read Beneficiary|null $beneficiary
 * @property-read Household|null $household
 */
class Enrollment extends Model implements MdaScoped
{
    /** @use HasFactory<EnrollmentFactory> */
    use Auditable, HasFactory, HasUuids, ScopedToMda, SoftDeletes;

    protected $table = 'enrollments';

    /** Scoped to the enrolling MDA, not an `owner_mda_id`. */
    public function mdaOwnershipColumn(): string
    {
        return 'mda_id';
    }

    /**
     * @var list<string>
     */
    protected $fillable = [
        'programme_id',
        'activity_id',
        'mda_id',
        'beneficiary_id',
        'household_id',
        'status',
        'enrolled_on',
        'exited_on',
        'exit_reason',
        'eligibility_flagged',
        'eligibility_notes',
        'enrolled_by',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => EnrollmentStatus::Enrolled->value,
        'eligibility_flagged' => false,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => EnrollmentStatus::class,
            'enrolled_on' => 'date',
            'exited_on' => 'date',
            'eligibility_flagged' => 'boolean',
            'eligibility_notes' => 'array',
        ];
    }

    protected static function newFactory(): EnrollmentFactory
    {
        return EnrollmentFactory::new();
    }

    /**
     * @return BelongsTo<Programme, $this>
     */
    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class);
    }

    /**
     * @return BelongsTo<Activity, $this>
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * @return BelongsTo<Mda, $this>
     */
    public function mda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'mda_id');
    }

    /**
     * @return BelongsTo<Beneficiary, $this>
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    /**
     * @return BelongsTo<Household, $this>
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }
}
