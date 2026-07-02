<?php

declare(strict_types=1);

namespace App\Domain\Registry\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\Mda;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Registry\Enums\RegistrationSource;
use Database\Factories\HouseholdFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * A household — optional grouping of beneficiaries (PRD FR-REG-01, §9).
 * Owned by an MDA and MDA-scoped via `owner_mda_id`; audited via Auditable.
 *
 * @property string $id
 * @property string $owner_mda_id
 * @property string|null $head_beneficiary_id
 * @property RegistrationSource $registration_source
 * @property Carbon $registration_date
 * @property string|null $import_batch_id
 * @property string|null $original_record_id
 * @property string|null $address
 * @property string|null $lga
 * @property string|null $ward
 * @property-read Mda|null $ownerMda
 * @property-read Beneficiary|null $head
 * @property-read Collection<int, HouseholdMembership> $memberships
 * @property-read Collection<int, HouseholdMembership> $currentMemberships
 */
class Household extends Model implements MdaScoped
{
    /** @use HasFactory<HouseholdFactory> */
    use Auditable, HasFactory, HasUuids, ScopedToMda, SoftDeletes;

    protected $table = 'households';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'owner_mda_id',
        'head_beneficiary_id',
        'registration_source',
        'registration_date',
        'import_batch_id',
        'original_record_id',
        'address',
        'lga',
        'ward',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'registration_source' => RegistrationSource::class,
            'registration_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Household $household): void {
            if (empty($household->registration_source)) {
                $household->registration_source = RegistrationSource::Manual;
            }
            if (empty($household->getAttribute('registration_date'))) {
                $household->registration_date = Carbon::today();
            }
        });
    }

    /**
     * @return BelongsTo<Mda, $this>
     */
    public function ownerMda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'owner_mda_id');
    }

    /**
     * @return BelongsTo<Beneficiary, $this>
     */
    public function head(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class, 'head_beneficiary_id');
    }

    /**
     * @return HasMany<HouseholdMembership, $this>
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(HouseholdMembership::class);
    }

    /**
     * The current (open) memberships — the household's present composition.
     *
     * @return HasMany<HouseholdMembership, $this>
     */
    public function currentMemberships(): HasMany
    {
        return $this->hasMany(HouseholdMembership::class)->whereNull('left_at');
    }

    protected static function newFactory(): HouseholdFactory
    {
        return HouseholdFactory::new();
    }
}
