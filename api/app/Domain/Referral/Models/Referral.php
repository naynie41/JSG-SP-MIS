<?php

declare(strict_types=1);

namespace App\Domain\Referral\Models;

use App\Domain\Access\Models\Mda;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Referral\Enums\ReferralStatus;
use App\Domain\Referral\Scopes\ReferralScope;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A referral of a beneficiary from one MDA to another for an identified need
 * (PRD FR-REF-01/02/04, §8.2). Two-party visible (see {@see ReferralScope}) and
 * Auditable; the transition columns are excluded from the generic update audit
 * because each transition is recorded explicitly (referral.accepted, …).
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $from_mda_id
 * @property string $to_mda_id
 * @property string $need
 * @property string|null $notes
 * @property ReferralStatus $status
 * @property int $escalation_level
 * @property Carbon|null $sla_breached_at
 * @property string|null $outcome
 * @property string|null $reason
 * @property string|null $info_request
 * @property string|null $info_response
 * @property string|null $created_by
 * @property Carbon|null $accepted_at
 * @property Carbon|null $rejected_at
 * @property Carbon|null $info_requested_at
 * @property Carbon|null $info_responded_at
 * @property Carbon|null $started_at
 * @property Carbon|null $completed_at
 * @property Carbon|null $closed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Beneficiary $beneficiary
 * @property-read Mda $fromMda
 * @property-read Mda $toMda
 */
class Referral extends Model
{
    use Auditable, HasUuids;

    protected $table = 'referrals';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'beneficiary_id',
        'from_mda_id',
        'to_mda_id',
        'need',
        'notes',
        'status',
        'escalation_level',
        'sla_breached_at',
        'outcome',
        'reason',
        'info_request',
        'info_response',
        'created_by',
        'accepted_at',
        'rejected_at',
        'info_requested_at',
        'info_responded_at',
        'started_at',
        'completed_at',
        'closed_at',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new ReferralScope);
    }

    /**
     * Whether an accepted (or in-progress) referral currently authorizes the given
     * MDA to serve/deliver to the beneficiary (PRD FR-REF-02, FR-BEN-06). This is
     * the referral's own authorization — SEPARATE from a Service Request grant; the
     * owner MDA already consented by referring, so ownership is never involved.
     */
    public static function authorizesDelivery(string $beneficiaryId, string $mdaId): bool
    {
        return static::query()
            ->withoutGlobalScope(ReferralScope::class)
            ->where('to_mda_id', $mdaId)
            ->where('beneficiary_id', $beneficiaryId)
            ->whereIn('status', [ReferralStatus::Accepted->value, ReferralStatus::InProgress->value])
            ->exists();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ReferralStatus::class,
            'escalation_level' => 'integer',
            'sla_breached_at' => 'datetime',
            'accepted_at' => 'datetime',
            'rejected_at' => 'datetime',
            'info_requested_at' => 'datetime',
            'info_responded_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    /**
     * Transitions are audited explicitly; keep them out of the generic update audit.
     *
     * @return list<string>
     */
    protected function auditExcluded(): array
    {
        return [
            'status', 'escalation_level', 'sla_breached_at',
            'outcome', 'reason', 'info_request', 'info_response',
            'accepted_at', 'rejected_at', 'info_requested_at', 'info_responded_at',
            'started_at', 'completed_at', 'closed_at',
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
    public function toMda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'to_mda_id');
    }
}
