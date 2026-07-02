<?php

declare(strict_types=1);

namespace App\Domain\Registry\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 * @property array<string, mixed> $payload
 * @property bool $is_valid
 * @property list<array{field: string, message: string}>|null $errors
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
        'payload',
        'is_valid',
        'errors',
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
            'is_valid' => 'boolean',
            'row_number' => 'integer',
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
