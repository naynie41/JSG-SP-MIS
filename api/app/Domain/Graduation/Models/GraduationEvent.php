<?php

declare(strict_types=1);

namespace App\Domain\Graduation\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\User;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Registry\Models\Household;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * The permanent record that a beneficiary/household graduated from a programme
 * (FR-GRD-02). Recording it flips the ENROLMENT status to graduated but NEVER deletes
 * the beneficiary or their benefit ledger — the full history is preserved.
 *
 * Scoped on `mda_id` — the MDA that ran the programme (the enrolling MDA) — so the
 * graduation history is visible to that MDA and to oversight roles.
 *
 * @property string $id
 * @property string $enrollment_id
 * @property string|null $beneficiary_id
 * @property string|null $household_id
 * @property string $programme_id
 * @property string|null $activity_id
 * @property string $mda_id
 * @property string|null $criteria_id
 * @property string|null $reason
 * @property string|null $decided_by
 * @property Carbon $graduated_at
 */
class GraduationEvent extends Model implements MdaScoped
{
    use HasUuids, ScopedToMda;

    protected $table = 'graduation_events';

    /** Scoped to the MDA that ran the programme, not an `owner_mda_id`. */
    public function mdaOwnershipColumn(): string
    {
        return 'mda_id';
    }

    /**
     * @var list<string>
     */
    protected $fillable = [
        'enrollment_id', 'beneficiary_id', 'household_id', 'programme_id', 'activity_id',
        'mda_id', 'criteria_id', 'reason', 'decided_by', 'graduated_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['graduated_at' => 'datetime'];
    }

    /**
     * @return BelongsTo<Enrollment, $this>
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    /**
     * @return BelongsTo<Beneficiary, $this>
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    /**
     * The household, when the enrolment was a household one.
     *
     * @return BelongsTo<Household, $this>
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * The officer who decided. A graduation is a judgement about a person's
     * circumstances, so the record names who made it — an id alone is not accountability.
     *
     * @return BelongsTo<User, $this>
     */
    public function decidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    /**
     * @return BelongsTo<GraduationCriteria, $this>
     */
    public function criteria(): BelongsTo
    {
        return $this->belongsTo(GraduationCriteria::class, 'criteria_id');
    }
}
