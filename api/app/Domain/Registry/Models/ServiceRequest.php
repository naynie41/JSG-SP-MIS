<?php

declare(strict_types=1);

namespace App\Domain\Registry\Models;

use App\Domain\Access\Models\Mda;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Registry\Enums\ServiceRequestStatus;
use App\Domain\Registry\Policies\OwnerMdaPolicy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A Service Request (PRD §12, FR-OWN-06): the requesting MDA (`from_mda_id`) asks
 * the owner MDA (`to_mda_id`) for permission to serve an existing beneficiary.
 * State machine: pending → accepted | declined. Ownership
 * (`beneficiaries.owner_mda_id`) is NEVER changed. Distinct from a Referral.
 *
 * Not globally MDA-scoped because it is a two-party record (requester + owner);
 * visibility is enforced by {@see OwnerMdaPolicy}
 * and the explicit inbox/outbox queries. Audited via Auditable — the decision
 * transitions are recorded explicitly (service_request.accepted/declined) so
 * status changes are excluded from the generic update audit.
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $from_mda_id
 * @property string $to_mda_id
 * @property string|null $activity_id
 * @property ServiceRequestStatus $status
 * @property string|null $reason
 * @property string|null $import_row_id
 * @property string|null $requested_by
 * @property string|null $decided_by
 * @property Carbon|null $decided_at
 * @property string|null $decision_reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Beneficiary $beneficiary
 * @property-read Mda $fromMda
 * @property-read Mda $toMda
 */
class ServiceRequest extends Model
{
    use Auditable, HasUuids;

    protected $table = 'service_requests';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'beneficiary_id',
        'from_mda_id',
        'to_mda_id',
        'activity_id',
        'status',
        'reason',
        'import_row_id',
        'requested_by',
        'decided_by',
        'decided_at',
        'decision_reason',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ServiceRequestStatus::class,
            'decided_at' => 'datetime',
        ];
    }

    /**
     * The decision transition is audited explicitly; keep it out of the generic
     * update audit to avoid a duplicate, less-meaningful entry.
     *
     * @return list<string>
     */
    protected function auditExcluded(): array
    {
        return ['status', 'decided_by', 'decided_at', 'decision_reason'];
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
