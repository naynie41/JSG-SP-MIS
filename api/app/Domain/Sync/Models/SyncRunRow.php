<?php

declare(strict_types=1);

namespace App\Domain\Sync\Models;

use App\Domain\Sync\Enums\SyncRowOutcome;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * The append-only, per-record outcome of a sync run (FR-DSH-02) — the sync log an
 * admin reads to see exactly what each record did. Never updated or deleted.
 *
 * @property string $id
 * @property string $sync_run_id
 * @property string|null $original_record_id
 * @property SyncRowOutcome $outcome
 * @property string|null $beneficiary_id
 * @property string|null $match_band
 * @property array<string, mixed>|null $detail
 * @property Carbon $created_at
 */
class SyncRunRow extends Model
{
    use HasUuids;

    protected $table = 'sync_run_rows';

    public $timestamps = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'sync_run_id',
        'original_record_id',
        'outcome',
        'beneficiary_id',
        'match_band',
        'detail',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'outcome' => SyncRowOutcome::class,
            'detail' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<SyncRun, $this>
     */
    public function run(): BelongsTo
    {
        return $this->belongsTo(SyncRun::class, 'sync_run_id');
    }
}
