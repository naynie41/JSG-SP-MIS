<?php

declare(strict_types=1);

namespace App\Domain\Registry\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\Mda;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Registry\Enums\BeneficiaryStatus;
use App\Domain\Registry\Enums\Gender;
use App\Domain\Registry\Enums\RegistrationSource;
use Database\Factories\BeneficiaryFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

/**
 * A beneficiary — the registry's core record (PRD FR-REG-01/03/04, FR-OWN-01).
 * Owned by an MDA and MDA-scoped via `owner_mda_id`; audited via Auditable.
 *
 * @property string $id
 * @property string $owner_mda_id
 * @property RegistrationSource $registration_source
 * @property Carbon $registration_date
 * @property string|null $import_batch_id
 * @property string|null $original_record_id
 * @property string|null $idempotency_key
 * @property string|null $nin
 * @property string|null $bvn
 * @property string|null $phone
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property Carbon|null $date_of_birth
 * @property Gender|null $gender
 * @property string|null $address
 * @property string|null $lga
 * @property string|null $ward
 * @property BeneficiaryStatus $status
 * @property-read Mda|null $ownerMda
 * @property-read HouseholdMembership|null $currentMembership
 */
class Beneficiary extends Model implements MdaScoped
{
    /** @use HasFactory<BeneficiaryFactory> */
    use Auditable, HasFactory, HasUuids, ScopedToMda, SoftDeletes;

    protected $table = 'beneficiaries';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'owner_mda_id',
        'registration_source',
        'registration_date',
        'import_batch_id',
        'original_record_id',
        'idempotency_key',
        'nin',
        'bvn',
        'phone',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'address',
        'lga',
        'ward',
        'status',
    ];

    /**
     * NIN/BVN are national identifiers — never exposed by default serialization;
     * screens reveal them (masked) through a permission-gated resource.
     *
     * @var list<string>
     */
    protected $hidden = [
        'nin',
        'bvn',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => BeneficiaryStatus::Active->value,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'registration_source' => RegistrationSource::class,
            'registration_date' => 'date',
            'date_of_birth' => 'date',
            'gender' => Gender::class,
            'status' => BeneficiaryStatus::class,
        ];
    }

    /**
     * A beneficiary's name is PII — masked in audit snapshots (SECURITY.md §6).
     * NIN/BVN/phone/address/DOB are masked by the global audit config.
     *
     * @return list<string>
     */
    protected function auditMask(): array
    {
        return ['first_name', 'middle_name', 'last_name'];
    }

    protected static function booted(): void
    {
        // Provenance defaults on creation (FR-REG-03): manual entry, dated today.
        static::creating(function (Beneficiary $beneficiary): void {
            if (empty($beneficiary->registration_source)) {
                $beneficiary->registration_source = RegistrationSource::Manual;
            }
            if (empty($beneficiary->getAttribute('registration_date'))) {
                $beneficiary->registration_date = Carbon::today();
            }
        });

        // Normalise identifiers on every save and enforce the 11-digit rule
        // (CONVENTIONS.md §6). Matching logic itself is Phase 3.
        static::saving(function (Beneficiary $beneficiary): void {
            $beneficiary->nin = self::normalizeDigits($beneficiary->nin);
            $beneficiary->bvn = self::normalizeDigits($beneficiary->bvn);
            $beneficiary->phone = self::normalizeDigits($beneficiary->phone);

            foreach (['nin' => $beneficiary->nin, 'bvn' => $beneficiary->bvn] as $field => $value) {
                if ($value !== null && preg_match('/^\d{11}$/', $value) !== 1) {
                    throw new InvalidArgumentException("The {$field} must be exactly 11 digits.");
                }
            }
        });
    }

    /**
     * Strip all non-digits; return null when nothing remains (so the column is
     * NULL, not an empty string — required for the partial unique indexes).
     */
    public static function normalizeDigits(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $digits = preg_replace('/\D+/', '', $value) ?? '';

        return $digits === '' ? null : $digits;
    }

    public function fullName(): string
    {
        return trim(implode(' ', array_filter([$this->first_name, $this->middle_name, $this->last_name])));
    }

    /**
     * @return BelongsTo<Mda, $this>
     */
    public function ownerMda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'owner_mda_id');
    }

    /**
     * @return HasMany<HouseholdMembership, $this>
     */
    public function householdMemberships(): HasMany
    {
        return $this->hasMany(HouseholdMembership::class);
    }

    /**
     * The beneficiary's current (open) household membership, if any.
     *
     * @return HasOne<HouseholdMembership, $this>
     */
    public function currentMembership(): HasOne
    {
        return $this->hasOne(HouseholdMembership::class)->whereNull('left_at');
    }

    protected static function newFactory(): BeneficiaryFactory
    {
        return BeneficiaryFactory::new();
    }
}
