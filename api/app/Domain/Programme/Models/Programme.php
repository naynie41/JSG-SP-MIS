<?php

declare(strict_types=1);

namespace App\Domain\Programme\Models;

use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Concerns\SharedWhenUnowned;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Programme\Enums\ProgrammeApproval;
use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Enums\ProgrammeType;
use App\Domain\Shared\Concerns\Archivable;
use Database\Factories\ProgrammeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * A catalog entry for a social-protection programme *type* (PRD §10, ARCH §12.4).
 * It carries type-level attributes only; budget, funding and period live on the
 * {@see Activity} that runs it. Auditable.
 *
 * Two kinds of entry, told apart by `owner_mda_id`:
 *
 *  - **Central catalog** (`owner_mda_id` NULL) — created by the System Administrator
 *    or SP Coordination and readable by every MDA, who run it through their own
 *    activities. This is what the catalog was, and every existing row is one.
 *  - **MDA-owned** (`owner_mda_id` set) — created by that MDA for itself. No other
 *    MDA can see it (the {@see SharedWhenUnowned} scope, not a controller filter),
 *    and it carries no work until a System Administrator approves it.
 *
 * `approval_status` is deliberately NOT the same field as `status`: `status` is the
 * delivery lifecycle (draft/active/closed/archived), the decision is its own axis.
 *
 * @property string $id
 * @property string|null $owner_mda_id
 * @property string $name
 * @property string|null $objective
 * @property ProgrammeType $type
 * @property string|null $benefit_category
 * @property string|null $target_group
 * @property bool $is_automated
 * @property array<int, array<string, mixed>>|null $eligibility
 * @property bool $enforce_eligibility
 * @property ProgrammeStatus $status
 * @property ProgrammeApproval $approval_status
 * @property Carbon|null $submitted_at
 * @property string|null $approved_by
 * @property Carbon|null $approved_at
 * @property string|null $decision_note
 * @property Carbon|null $archived_at
 * @property string|null $archived_by
 * @property string|null $archive_reason
 * @property string|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 */
class Programme extends Model implements SharedWhenUnowned
{
    /** @use HasFactory<ProgrammeFactory> */
    use Archivable, Auditable, HasFactory, HasUuids, ScopedToMda, SoftDeletes;

    protected $table = 'programmes';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'owner_mda_id',
        'name',
        'objective',
        'type',
        'benefit_category',
        'target_group',
        'is_automated',
        'eligibility',
        'enforce_eligibility',
        'status',
        'created_by',
        // `approval_status`, `submitted_at`, `approved_by`, `approved_at` and
        // `decision_note` are deliberately NOT fillable. The decision moves only
        // through submit()/approve()/reject(), so no PATCH can self-approve a
        // programme by including the field.
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => ProgrammeStatus::Draft->value,
        'approval_status' => ProgrammeApproval::Approved->value,
        'enforce_eligibility' => false,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => ProgrammeType::class,
            'status' => ProgrammeStatus::class,
            'approval_status' => ProgrammeApproval::class,
            'submitted_at' => 'datetime',
            'approved_at' => 'datetime',
            'archived_at' => 'datetime',
            'is_automated' => 'boolean',
            'eligibility' => 'array',
            'enforce_eligibility' => 'boolean',
        ];
    }

    protected static function newFactory(): ProgrammeFactory
    {
        return ProgrammeFactory::new();
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return HasMany<Activity, $this>
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * The MDA that owns this programme, or none for the central catalog. Mda is
     * itself MDA-scoped, which is right here: whoever can see the programme can
     * already see the MDA that owns it.
     *
     * @return BelongsTo<Mda, $this>
     */
    public function ownerMda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'owner_mda_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Only programmes cleared for use. Reporting counts this, never the submissions:
     * a state-wide "programmes" figure that included things nobody has agreed to yet
     * would describe intent as if it were delivery.
     *
     * @param  Builder<Programme>  $query
     * @return Builder<Programme>
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approval_status', ProgrammeApproval::Approved->value);
    }

    /** Central catalog entry — no MDA owns it and every MDA may run it (§10). */
    public function isCentral(): bool
    {
        return $this->owner_mda_id === null;
    }

    /** Cleared for use. Everything central is; an MDA's own must be decided first. */
    public function isApproved(): bool
    {
        return $this->approval_status === ProgrammeApproval::Approved;
    }

    /**
     * Put an MDA's programme in front of the System Administrator. Used on create
     * and again after a rejection has been addressed.
     */
    public function submitForApproval(): void
    {
        $this->forceFill([
            'approval_status' => ProgrammeApproval::Pending,
            'submitted_at' => Carbon::now(),
            'approved_by' => null,
            'approved_at' => null,
            'decision_note' => null,
        ])->save();
    }

    /** Approve it, recording who decided and when (FR-PRG-01, audited). */
    public function approveBy(User $approver, ?string $note = null): void
    {
        $this->forceFill([
            'approval_status' => ProgrammeApproval::Approved,
            'approved_by' => $approver->id,
            'approved_at' => Carbon::now(),
            'decision_note' => $note,
        ])->save();
    }

    /** Send it back with a reason the owning MDA can act on. */
    public function rejectBy(User $approver, string $reason): void
    {
        $this->forceFill([
            'approval_status' => ProgrammeApproval::Rejected,
            'approved_by' => $approver->id,
            'approved_at' => Carbon::now(),
            'decision_note' => $reason,
        ])->save();
    }
}
