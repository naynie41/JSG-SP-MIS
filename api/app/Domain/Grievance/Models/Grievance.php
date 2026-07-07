<?php

declare(strict_types=1);

namespace App\Domain\Grievance\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Grievance\Enums\GrievanceCategory;
use App\Domain\Grievance\Enums\GrievanceChannel;
use App\Domain\Grievance\Enums\GrievanceStatus;
use App\Domain\Registry\Models\Beneficiary;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A grievance handled within one MDA (PRD FR-GRM-01/02). MDA-scoped on
 * `handling_mda_id` (only that MDA + oversight see it) and Auditable; transitions
 * are audited explicitly so the volatile columns are excluded from the generic
 * update audit.
 *
 * @property string $id
 * @property string $handling_mda_id
 * @property string|null $beneficiary_id
 * @property GrievanceCategory $category
 * @property GrievanceChannel $channel
 * @property string $description
 * @property GrievanceStatus $status
 * @property string|null $assignee_user_id
 * @property string|null $resolution_notes
 * @property string|null $submitted_by
 * @property int $escalation_level
 * @property Carbon|null $assigned_at
 * @property Carbon|null $started_at
 * @property Carbon|null $resolved_at
 * @property Carbon|null $closed_at
 * @property Carbon|null $sla_breached_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Mda $handlingMda
 * @property-read Beneficiary|null $beneficiary
 * @property-read User|null $assignee
 */
class Grievance extends Model implements MdaScoped
{
    use Auditable, HasUuids, ScopedToMda;

    protected $table = 'grievances';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'handling_mda_id',
        'beneficiary_id',
        'category',
        'channel',
        'description',
        'status',
        'assignee_user_id',
        'resolution_notes',
        'submitted_by',
        'escalation_level',
        'assigned_at',
        'started_at',
        'resolved_at',
        'closed_at',
        'sla_breached_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'category' => GrievanceCategory::class,
            'channel' => GrievanceChannel::class,
            'status' => GrievanceStatus::class,
            'escalation_level' => 'integer',
            'assigned_at' => 'datetime',
            'started_at' => 'datetime',
            'resolved_at' => 'datetime',
            'closed_at' => 'datetime',
            'sla_breached_at' => 'datetime',
        ];
    }

    /** Scoped to the handling MDA, not the Phase 2 owner column. */
    public function mdaOwnershipColumn(): string
    {
        return 'handling_mda_id';
    }

    /**
     * Transitions/assignment are audited explicitly; keep them out of the generic
     * update audit.
     *
     * @return list<string>
     */
    protected function auditExcluded(): array
    {
        return [
            'status', 'assignee_user_id', 'resolution_notes', 'escalation_level',
            'assigned_at', 'started_at', 'resolved_at', 'closed_at', 'sla_breached_at',
        ];
    }

    /**
     * @return BelongsTo<Mda, $this>
     */
    public function handlingMda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'handling_mda_id');
    }

    /**
     * @return BelongsTo<Beneficiary, $this>
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_user_id');
    }
}
