<?php

declare(strict_types=1);

namespace App\Domain\Registry\Models;

use App\Domain\Access\Models\Mda;
use App\Domain\Audit\Concerns\Auditable;
use App\Domain\Registry\Enums\ServeRequestStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A request-to-serve an existing beneficiary (PRD FR-DUP-05). The requesting MDA
 * (`from_mda_id`) asks the owner MDA (`to_mda_id`) for serve access; ownership
 * (`beneficiaries.owner_mda_id`) is never changed. Audited via Auditable.
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $from_mda_id
 * @property string $to_mda_id
 * @property ServeRequestStatus $status
 * @property string|null $reason
 * @property string|null $import_row_id
 * @property string|null $requested_by
 * @property string|null $decided_by
 * @property Carbon|null $decided_at
 * @property string|null $decision_reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Beneficiary $beneficiary
 * @property-read Mda $fromMda
 * @property-read Mda $toMda
 */
class ServeRequest extends Model
{
    use Auditable, HasUuids;

    protected $table = 'serve_requests';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'beneficiary_id',
        'from_mda_id',
        'to_mda_id',
        'status',
        'reason',
        'import_row_id',
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
            'status' => ServeRequestStatus::class,
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
}
