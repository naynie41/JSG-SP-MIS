<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\Mda;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Benefit\Enums\BenefitStatus;
use App\Domain\Benefit\Enums\BenefitType;
use App\Domain\Benefit\Enums\VerificationMethod;
use App\Domain\Programme\Models\Activity;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Programme\Models\Programme;
use App\Domain\Registry\Models\Beneficiary;
use Database\Factories\BenefitFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * A benefit-ledger entry: one benefit DELIVERED to a beneficiary (PRD FR-BEN-01/02).
 * Records delivery only — never a money movement (§2.3); `monetary_value` is data.
 *
 * Scoped on `mda_id` — the DELIVERING MDA — which may differ from the beneficiary's
 * owner MDA (a serving MDA delivers without owning). Auditable.
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $programme_id
 * @property string|null $activity_id
 * @property string|null $enrollment_id
 * @property string $mda_id
 * @property BenefitType $benefit_type
 * @property string|null $quantity
 * @property string|null $unit
 * @property int|null $monetary_value
 * @property string|null $funding_source
 * @property Carbon $delivery_date
 * @property string|null $lga
 * @property string|null $ward
 * @property BenefitStatus $status
 * @property VerificationMethod $verification_method
 * @property string|null $verification_reference
 * @property string|null $verified_by
 * @property Carbon|null $verified_at
 * @property string|null $recorded_by
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Beneficiary $beneficiary
 * @property-read Programme $programme
 * @property-read Mda $mda
 */
class Benefit extends Model implements MdaScoped
{
    /** @use HasFactory<BenefitFactory> */
    use Auditable, HasFactory, HasUuids, ScopedToMda, SoftDeletes;

    protected $table = 'benefits';

    /** Scoped to the delivering MDA, not an `owner_mda_id`. */
    public function mdaOwnershipColumn(): string
    {
        return 'mda_id';
    }

    /**
     * @var list<string>
     */
    protected $fillable = [
        'beneficiary_id',
        'programme_id',
        'activity_id',
        'enrollment_id',
        'mda_id',
        'benefit_type',
        'quantity',
        'unit',
        'monetary_value',
        'funding_source',
        'delivery_date',
        'lga',
        'ward',
        'status',
        'verification_method',
        'verification_reference',
        'verified_by',
        'verified_at',
        'recorded_by',
        'notes',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => BenefitStatus::Recorded->value,
        'verification_method' => VerificationMethod::None->value,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'benefit_type' => BenefitType::class,
            'status' => BenefitStatus::class,
            'verification_method' => VerificationMethod::class,
            'quantity' => 'decimal:3',
            'monetary_value' => 'integer',
            'delivery_date' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    protected static function newFactory(): BenefitFactory
    {
        return BenefitFactory::new();
    }

    /**
     * @return BelongsTo<Beneficiary, $this>
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
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
     * @return BelongsTo<Enrollment, $this>
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * @return BelongsTo<Mda, $this>
     */
    public function mda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'mda_id');
    }
}
