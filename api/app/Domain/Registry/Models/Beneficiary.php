<?php

declare(strict_types=1);

namespace App\Domain\Registry\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\Mda;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Matching\Support\PhoneticEncoder;
use App\Domain\Programme\Models\Enrollment;
use App\Domain\Registry\Enums\BeneficiaryStatus;
use App\Domain\Registry\Enums\ConsentStatus;
use App\Domain\Registry\Enums\Gender;
use App\Domain\Registry\Enums\RegistrationSource;
use App\Domain\Registry\Support\IdentifierHasher;
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
 * @property string|null $nin encrypted at rest; exact-match via $nin_hash
 * @property string|null $bvn encrypted at rest; exact-match via $bvn_hash
 * @property string|null $nin_hash
 * @property string|null $bvn_hash
 * @property string|null $phone
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property Carbon|null $date_of_birth
 * @property Gender|null $gender
 * @property string|null $address
 * @property string|null $lga
 * @property string|null $ward
 * @property string|null $block_name_dob
 * @property BeneficiaryStatus $status
 * @property Carbon|null $retention_flagged_at
 * @property Carbon|null $anonymized_at
 * @property string|null $retention_policy
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
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
        'block_name_dob',
        'status',
        'sharing_consent',
        'sharing_consent_at',
        'retention_flagged_at',
        'anonymized_at',
        'retention_policy',
    ];

    /**
     * NIN/BVN are national identifiers — never exposed by default serialization;
     * screens reveal them (masked) through a permission-gated resource. The
     * `*_hash` columns are the operational lookup keys (never useful to clients).
     *
     * @var list<string>
     */
    protected $hidden = [
        'nin',
        'bvn',
        'nin_hash',
        'bvn_hash',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => BeneficiaryStatus::Active->value,
        'sharing_consent' => ConsentStatus::Unknown->value,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // National identifiers are encrypted at rest (SECURITY.md §4); exact
            // matching runs on the keyed nin_hash/bvn_hash columns instead.
            'nin' => 'encrypted',
            'bvn' => 'encrypted',
            'registration_source' => RegistrationSource::class,
            'registration_date' => 'date',
            'date_of_birth' => 'date',
            'gender' => Gender::class,
            'status' => BeneficiaryStatus::class,
            'sharing_consent' => ConsentStatus::class,
            'sharing_consent_at' => 'datetime',
            'retention_flagged_at' => 'datetime',
            'anonymized_at' => 'datetime',
        ];
    }

    /** Whether the record has been de-identified by a retention policy (NFR-PRV-01). */
    public function isAnonymized(): bool
    {
        return $this->anonymized_at !== null;
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

    /**
     * Derived operational columns (blocking key, identifier hashes) — keep them
     * out of the audit trail; they add nothing to "who changed what".
     *
     * @return list<string>
     */
    protected function auditExcluded(): array
    {
        return ['block_name_dob', 'nin_hash', 'bvn_hash'];
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

        // Normalise identifiers on every save, enforce the 11-digit rule
        // (CONVENTIONS.md §6), maintain the fuzzy-matching blocking key, and keep
        // the deterministic identifier hashes (exact matching + uniqueness run on
        // the hashes because the values themselves are encrypted at rest).
        static::saving(function (Beneficiary $beneficiary): void {
            $beneficiary->nin = self::normalizeDigits($beneficiary->nin);
            $beneficiary->bvn = self::normalizeDigits($beneficiary->bvn);
            $beneficiary->phone = self::normalizeDigits($beneficiary->phone);

            foreach (['nin' => $beneficiary->nin, 'bvn' => $beneficiary->bvn] as $field => $value) {
                if ($value !== null && preg_match('/^\d{11}$/', $value) !== 1) {
                    throw new InvalidArgumentException("The {$field} must be exactly 11 digits.");
                }
            }

            $beneficiary->nin_hash = IdentifierHasher::hash($beneficiary->nin);
            $beneficiary->bvn_hash = IdentifierHasher::hash($beneficiary->bvn);

            $beneficiary->block_name_dob = self::blockNameDobFor(
                $beneficiary->last_name,
                $beneficiary->date_of_birth?->toDateString(),
            );
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

    /**
     * The fuzzy-matching blocking key: phonetic(last_name) | dob_year (PRD
     * FR-DUP-03). Used both to maintain the column and to build the gather query.
     */
    public static function blockNameDobFor(?string $lastName, ?string $dob): ?string
    {
        $code = $lastName !== null && $lastName !== '' ? (new PhoneticEncoder)->block($lastName) : '';
        $year = $dob !== null && $dob !== '' && ($ts = strtotime($dob)) !== false ? date('Y', $ts) : '';

        return $code === '' && $year === '' ? null : $code.'|'.$year;
    }

    public function fullName(): string
    {
        return trim(implode(' ', array_filter([$this->first_name, $this->middle_name, $this->last_name])));
    }

    /** Whether cross-MDA data-sharing consent is currently granted (NFR-PRV-01). */
    public function hasSharingConsent(): bool
    {
        return $this->sharing_consent === ConsentStatus::Granted;
    }

    /**
     * Append-only consent history (most recent first).
     *
     * @return HasMany<BeneficiaryConsent, $this>
     */
    public function consents(): HasMany
    {
        return $this->hasMany(BeneficiaryConsent::class)->latest('created_at');
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
     * The benefit-ledger history (cross-MDA — the ledger belongs to the subject, not
     * one MDA). Bypasses the delivering-MDA scope so the FULL history is available for
     * right-of-access and the retention "has history" check.
     *
     * @return HasMany<Benefit, $this>
     */
    public function benefits(): HasMany
    {
        return $this->hasMany(Benefit::class)->withoutGlobalScope(MdaScope::class);
    }

    /**
     * @return HasMany<Enrollment, $this>
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class)->withoutGlobalScope(MdaScope::class);
    }

    /**
     * @return HasMany<BeneficiaryDocument, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(BeneficiaryDocument::class)->withoutGlobalScope(MdaScope::class);
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
