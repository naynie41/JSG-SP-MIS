<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Models;

use App\Domain\Access\Models\Mda;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Benefit\Enums\BenefitFlagStatus;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A potential double-dipping flag (PRD FR-BEN-05) between two deliveries of the
 * same benefit type to the same beneficiary by different MDAs. Advisory only —
 * never blocks. Audited.
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $benefit_id
 * @property string $related_benefit_id
 * @property string|null $rule_id
 * @property string $benefit_type
 * @property string $from_mda_id
 * @property string $other_mda_id
 * @property BenefitFlagStatus $status
 * @property string $reason
 * @property string|null $reviewed_by
 * @property Carbon|null $reviewed_at
 * @property string|null $review_note
 * @property Carbon|null $created_at
 */
class BenefitFlag extends Model
{
    use Auditable, HasUuids;

    protected $table = 'benefit_flags';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'beneficiary_id', 'benefit_id', 'related_benefit_id', 'rule_id', 'benefit_type',
        'from_mda_id', 'other_mda_id', 'status', 'reason', 'reviewed_by', 'reviewed_at', 'review_note',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = ['status' => BenefitFlagStatus::Open->value];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => BenefitFlagStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Beneficiary, $this>
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    /**
     * @return BelongsTo<Mda, $this>
     */
    public function fromMda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'from_mda_id');
    }

    /**
     * @return BelongsTo<Mda, $this>
     */
    public function otherMda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'other_mda_id');
    }
}
