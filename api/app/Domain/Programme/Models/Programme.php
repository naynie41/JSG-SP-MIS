<?php

declare(strict_types=1);

namespace App\Domain\Programme\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Programme\Enums\ProgrammeStatus;
use App\Domain\Programme\Enums\ProgrammeType;
use Database\Factories\ProgrammeFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * A social-protection programme run by an MDA (PRD FR-PRG-01). The `owner_mda_id`
 * is the ownership + scoping column — only the owner MDA may mutate it (enforced
 * by ProgrammePolicy + MdaScope). Auditable; monetary budget is integer minor
 * units (kobo, NGN).
 *
 * @property string $id
 * @property string $owner_mda_id
 * @property string $name
 * @property string|null $objective
 * @property ProgrammeType $type
 * @property array<int, array<string, mixed>>|null $eligibility
 * @property bool $enforce_eligibility
 * @property string|null $funding_source
 * @property int|null $budget_amount
 * @property Carbon|null $starts_on
 * @property Carbon|null $ends_on
 * @property ProgrammeStatus $status
 * @property string|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Mda $ownerMda
 * @property-read Collection<int, Activity> $activities
 */
class Programme extends Model implements MdaScoped
{
    /** @use HasFactory<ProgrammeFactory> */
    use Auditable, HasFactory, HasUuids, ScopedToMda, SoftDeletes;

    protected $table = 'programmes';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'owner_mda_id',
        'name',
        'objective',
        'type',
        'eligibility',
        'enforce_eligibility',
        'funding_source',
        'budget_amount',
        'starts_on',
        'ends_on',
        'status',
        'created_by',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => ProgrammeStatus::Draft->value,
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
            'eligibility' => 'array',
            'enforce_eligibility' => 'boolean',
            'starts_on' => 'date',
            'ends_on' => 'date',
            'budget_amount' => 'integer',
        ];
    }

    protected static function newFactory(): ProgrammeFactory
    {
        return ProgrammeFactory::new();
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

    /**
     * @return HasMany<Activity, $this>
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
