<?php

declare(strict_types=1);

namespace App\Domain\Registry\Models;

use App\Domain\Registry\Enums\ConsentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * One entry in a beneficiary's append-only consent history (NFR-PRV-01). Each capture
 * or withdrawal writes a new row; the current status is denormalised onto the
 * beneficiary. Never updated or deleted — the lifecycle is the audit.
 *
 * @property string $id
 * @property string $beneficiary_id
 * @property string $purpose
 * @property ConsentStatus $status
 * @property string|null $basis
 * @property string|null $source
 * @property string|null $note
 * @property string|null $recorded_by
 * @property Carbon $created_at
 */
class BeneficiaryConsent extends Model
{
    use HasUuids;

    protected $table = 'beneficiary_consents';

    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'beneficiary_id',
        'purpose',
        'status',
        'basis',
        'source',
        'note',
        'recorded_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ConsentStatus::class,
            'created_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Beneficiary, $this>
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }
}
