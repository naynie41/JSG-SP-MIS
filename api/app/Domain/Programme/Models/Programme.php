<?php

declare(strict_types=1);

namespace App\Domain\Programme\Models;

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
 * A GLOBAL catalog entry for a social-protection programme *type* (PRD §10, ARCH
 * §12.4). It is NOT owned or scoped by any MDA — created only by the System
 * Administrator (optionally SP Coordination) and readable by all MDAs, who run it
 * through their own {@see Activity}. It carries type-level attributes only; budget,
 * funding and period live on the Activity. Auditable.
 *
 * @property string $id
 * @property string $name
 * @property string|null $objective
 * @property ProgrammeType $type
 * @property string|null $benefit_category
 * @property array<int, array<string, mixed>>|null $eligibility
 * @property bool $enforce_eligibility
 * @property ProgrammeStatus $status
 * @property string|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Activity> $activities
 */
class Programme extends Model
{
    /** @use HasFactory<ProgrammeFactory> */
    use Auditable, HasFactory, HasUuids, SoftDeletes;

    protected $table = 'programmes';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'objective',
        'type',
        'benefit_category',
        'eligibility',
        'enforce_eligibility',
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
}
