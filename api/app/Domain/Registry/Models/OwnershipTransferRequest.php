<?php

declare(strict_types=1);

namespace App\Domain\Registry\Models;

use App\Domain\Access\Models\Mda;
use App\Domain\Access\Models\User;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Registry\Enums\OwnershipTransferStatus;
use Database\Factories\OwnershipTransferRequestFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A request to transfer beneficiary ownership between MDAs (PRD FR-OWN-05).
 * Audited via Auditable so request + decision are on the record.
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $from_mda_id
 * @property string $to_mda_id
 * @property OwnershipTransferStatus $status
 * @property string|null $reason
 * @property string|null $requested_by
 * @property string|null $decided_by
 * @property Carbon|null $decided_at
 * @property string|null $decision_reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Beneficiary $beneficiary
 */
class OwnershipTransferRequest extends Model
{
    /** @use HasFactory<OwnershipTransferRequestFactory> */
    use Auditable, HasFactory, HasUuids;

    protected $table = 'ownership_transfer_requests';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'beneficiary_id',
        'from_mda_id',
        'to_mda_id',
        'status',
        'reason',
        'requested_by',
        'decided_by',
        'decided_at',
        'decision_reason',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => OwnershipTransferStatus::class,
            'decided_at' => 'datetime',
        ];
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
    public function fromMda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'from_mda_id');
    }

    /**
     * @return BelongsTo<Mda, $this>
     */
    public function toMda(): BelongsTo
    {
        return $this->belongsTo(Mda::class, 'to_mda_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    protected static function newFactory(): OwnershipTransferRequestFactory
    {
        return OwnershipTransferRequestFactory::new();
    }
}
