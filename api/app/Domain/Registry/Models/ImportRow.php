<?php

declare(strict_types=1);

namespace App\Domain\Registry\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * One staged source row of a bulk import (PRD FR-REG-06). Holds the normalised
 * values and the row-level validation result; `beneficiary_id` is set when the
 * row is committed, which makes commits idempotent. Not MDA-scoped directly —
 * always reached through its (scoped, authorized) ImportBatch.
 *
 * @property string $id
 * @property string $import_batch_id
 * @property int $row_number
 * @property string|null $original_record_id
 * @property string|null $household_ref
 * @property string|null $household_role
 * @property bool $household_head
 * @property array<string, mixed> $payload
 * @property bool $is_valid
 * @property list<array{field: string, message: string}>|null $errors
 * @property string|null $match_band
 * @property list<array<string, mixed>>|null $match_candidates
 * @property string|null $resolution
 * @property string|null $resolution_note
 * @property string|null $resolved_beneficiary_id
 * @property string|null $resolved_by
 * @property Carbon|null $resolved_at
 * @property string|null $beneficiary_id
 * @property-read ImportBatch $batch
 * @property-read Beneficiary|null $beneficiary
 */
class ImportRow extends Model
{
    use HasUuids;

    protected $table = 'import_rows';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'import_batch_id',
        'row_number',
        'original_record_id',
        'household_ref',
        'household_role',
        'household_head',
        'payload',
        'is_valid',
        'errors',
        'match_band',
        'match_candidates',
        'resolution',
        'resolution_note',
        'resolved_beneficiary_id',
        'resolved_by',
        'resolved_at',
        'beneficiary_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'errors' => 'array',
            'match_candidates' => 'array',
            'is_valid' => 'boolean',
            'household_head' => 'boolean',
            'row_number' => 'integer',
            'resolved_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<ImportBatch, $this>
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(ImportBatch::class, 'import_batch_id');
    }

    /**
     * @return BelongsTo<Beneficiary, $this>
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class, 'beneficiary_id');
    }
}
