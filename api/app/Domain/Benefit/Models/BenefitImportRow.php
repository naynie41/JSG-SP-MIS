<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Models;

use App\Domain\Registry\Models\Beneficiary;
use Database\Factories\BenefitImportRowFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One staged row of a benefit-delivery import (PRD FR-BEN-01/02, §8.3): a
 * reference to an existing beneficiary plus the parsed benefit fields and its
 * validation result. `benefit_id` is set on commit (idempotency). Not MDA-scoped
 * directly — reached through its (scoped) batch.
 *
 * @property string $id
 * @property string $benefit_import_batch_id
 * @property int $row_number
 * @property bool $is_valid
 * @property list<array{field: string, message: string}>|null $errors
 * @property bool $eligibility_flagged
 * @property string|null $resolved_beneficiary_id
 * @property array<string, mixed> $payload
 * @property string|null $benefit_id
 */
class BenefitImportRow extends Model
{
    /** @use HasFactory<BenefitImportRowFactory> */
    use HasFactory, HasUuids;

    protected $table = 'benefit_import_rows';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'benefit_import_batch_id',
        'row_number',
        'is_valid',
        'errors',
        'eligibility_flagged',
        'resolved_beneficiary_id',
        'payload',
        'benefit_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_valid' => 'boolean',
            'eligibility_flagged' => 'boolean',
            'errors' => 'array',
            'payload' => 'array',
            'row_number' => 'integer',
        ];
    }

    protected static function newFactory(): BenefitImportRowFactory
    {
        return BenefitImportRowFactory::new();
    }

    /**
     * @return BelongsTo<BenefitImportBatch, $this>
     */
    public function batch(): BelongsTo
    {
        return $this->belongsTo(BenefitImportBatch::class, 'benefit_import_batch_id');
    }

    /**
     * @return BelongsTo<Beneficiary, $this>
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class, 'resolved_beneficiary_id');
    }
}
