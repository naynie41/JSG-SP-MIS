<?php

declare(strict_types=1);

namespace App\Domain\Registry\Models;

use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Registry\Enums\HouseholdRole;
use Database\Factories\HouseholdMembershipFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A beneficiary's membership of a household over time. Open while `left_at` is
 * null; the DB enforces at most one open membership per beneficiary. Audited so
 * moves/splits are traceable.
 *
 * @property string $id
 * @property string $household_id
 * @property string $beneficiary_id
 * @property HouseholdRole $role_in_household
 * @property Carbon $joined_at
 * @property Carbon|null $left_at
 * @property-read Household $household
 * @property-read Beneficiary $beneficiary
 */
class HouseholdMembership extends Model
{
    /** @use HasFactory<HouseholdMembershipFactory> */
    use Auditable, HasFactory, HasUuids;

    protected $table = 'household_memberships';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'household_id',
        'beneficiary_id',
        'role_in_household',
        'joined_at',
        'left_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role_in_household' => HouseholdRole::class,
            'joined_at' => 'datetime',
            'left_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (HouseholdMembership $membership): void {
            if (empty($membership->getAttribute('joined_at'))) {
                $membership->joined_at = Carbon::now();
            }
        });
    }

    /**
     * @return BelongsTo<Household, $this>
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    /**
     * @return BelongsTo<Beneficiary, $this>
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    protected static function newFactory(): HouseholdMembershipFactory
    {
        return HouseholdMembershipFactory::new();
    }
}
