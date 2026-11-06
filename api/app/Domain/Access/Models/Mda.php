<?php

declare(strict_types=1);

namespace App\Domain\Access\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Enums\MdaStatus;
use App\Domain\Access\Enums\MdaType;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Programme\Models\Programme;
use App\Domain\Programme\Models\ProgrammeAssignment;
use Database\Factories\MdaFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * An organisation that DELIVERS social protection, and owns the records it
 * originates. Usually a Ministry, Department or Agency — hence the name — but a
 * development partner that implements its own programmes is one too
 * ({@see MdaType::Partner}).
 *
 * A partner organisation is deliberately modelled here rather than as a separate
 * kind of thing: ownership, MDA scoping, duplicate detection, request-to-serve and
 * the benefit ledger all key off `owner_mda_id`, so a partner that owns records
 * inherits every one of those rules for free. `funder_user_id` ties such an
 * organisation to the Development Partner ACCOUNT that funds through it; the two
 * are separate logins on purpose, so the funder role still never reaches PII
 * (CLAUDE.md §11).
 *
 * @property string $id
 * @property string $name
 * @property MdaType $type
 * @property string|null $funder_user_id
 * @property MdaStatus $status
 * @property string|null $contact_person
 * @property string|null $contact_email
 * @property string|null $contact_phone
 * @property string|null $address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Mda extends Model implements MdaScoped
{
    /** @use HasFactory<MdaFactory> */
    use Auditable, HasFactory, HasUuids, ScopedToMda, SoftDeletes;

    protected $table = 'mdas';

    /**
     * An MDA is scoped on its own primary key: a user sees their own MDA (and
     * any granted MDAs), unless they hold cross-mda.view (FR-UAM-03).
     */
    public function mdaOwnershipColumn(): string
    {
        return 'id';
    }

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'type',
        'funder_user_id',
        'contact_person',
        'contact_email',
        'contact_phone',
        'address',
        'status',
    ];

    /**
     * Model-level default mirroring the database default.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => MdaStatus::Active->value,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => MdaType::class,
            'status' => MdaStatus::class,
        ];
    }

    /**
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * The catalog programmes this MDA has been ASSIGNED and may therefore deliver
     * (§10, revised 2026-11-05).
     *
     * The assignment is what the MDA's programme pickers are built from, so this is
     * the answer to "what does this organisation offer" — not every programme in the
     * state catalog, which is what those pickers used to show.
     *
     * Deliberately NOT ownership: an assigned programme stays a central catalog entry
     * (`owner_mda_id` NULL) that several MDAs may be assigned, each running it through
     * its own activities. Removing an assignment removes permission to start new work,
     * never the programme and never the history under it.
     *
     * @return BelongsToMany<Programme, $this, ProgrammeAssignment, 'pivot'>
     */
    public function assignedProgrammes(): BelongsToMany
    {
        return $this->belongsToMany(Programme::class, 'mda_programme', 'mda_id', 'programme_id')
            ->using(ProgrammeAssignment::class)
            ->withPivot(['id', 'source', 'assigned_by'])
            ->withTimestamps();
    }

    /**
     * Cross-MDA grants that give other users access to this MDA.
     *
     * @return HasMany<MdaAccessGrant, $this>
     */
    public function accessGrants(): HasMany
    {
        return $this->hasMany(MdaAccessGrant::class);
    }

    /**
     * The Development Partner ACCOUNT this organisation funds through, for a partner
     * that both funds and implements. Null for every government MDA.
     *
     * Read without the MDA scope: the funder account carries no `mda_id` of its own
     * (it is state-level, funded-scope), so a scoped read would find nothing.
     *
     * @return BelongsTo<User, $this>
     */
    public function funderAccount(): BelongsTo
    {
        return $this->belongsTo(User::class, 'funder_user_id')->withoutGlobalScopes();
    }

    /** A government body, as opposed to a partner organisation that implements. */
    public function isGovernment(): bool
    {
        return $this->type->isGovernment();
    }

    protected static function newFactory(): MdaFactory
    {
        return MdaFactory::new();
    }
}
