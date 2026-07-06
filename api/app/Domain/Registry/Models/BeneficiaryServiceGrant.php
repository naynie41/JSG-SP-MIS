<?php

declare(strict_types=1);

namespace App\Domain\Registry\Models;

use App\Domain\Access\Concerns\MdaScoped;
use App\Domain\Access\Concerns\ScopedToMda;
use App\Domain\Access\Models\Mda;
use App\Domain\Audit\Concerns\Auditable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Explicit READ-access grant (PRD §12, FR-OWN-07): opened when the owner MDA
 * accepts a {@see ServiceRequest}. It gives the requesting MDA (`mda_id`) READ
 * access to the full beneficiary record — nothing else; ownership/edit never move.
 *
 * MDA-scoped on `mda_id` (the granted MDA) so an MDA sees only its own grants.
 * Auditable, but the semantic event (`beneficiary.access_granted`) is recorded
 * explicitly against the beneficiary when the grant opens.
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $mda_id
 * @property string $service_request_id
 * @property string|null $granted_by
 * @property Carbon $granted_at
 * @property Carbon|null $revoked_at
 * @property-read Beneficiary $beneficiary
 * @property-read Mda $mda
 */
class BeneficiaryServiceGrant extends Model implements MdaScoped
{
    use Auditable, HasUuids, ScopedToMda;

    protected $table = 'beneficiary_service_grants';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'beneficiary_id',
        'mda_id',
        'service_request_id',
        'granted_by',
        'granted_at',
        'revoked_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'granted_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    /** The grant is scoped to the granted (requesting) MDA, not an owner column. */
    public function mdaOwnershipColumn(): string
    {
        return 'mda_id';
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
    public function mda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'mda_id');
    }
}
