<?php

declare(strict_types=1);

namespace App\Domain\Benefit\Models;

use App\Domain\Audit\Concerns\Auditable;
use Database\Factories\DoubleDippingRuleFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * A configurable double-dipping rule (PRD FR-BEN-05): a window plus the benefit
 * types it applies to. System-wide, admin-editable, audited.
 *
 * @property string $id
 * @property string $name
 * @property int $period_days
 * @property list<string>|null $benefit_types
 * @property bool $is_active
 * @property string|null $description
 * @property string|null $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class DoubleDippingRule extends Model
{
    /** @use HasFactory<DoubleDippingRuleFactory> */
    use Auditable, HasFactory, HasUuids, SoftDeletes;

    protected $table = 'double_dipping_rules';

    /**
     * @var list<string>
     */
    protected $fillable = ['name', 'period_days', 'benefit_types', 'is_active', 'description', 'created_by'];

    /**
     * @var array<string, mixed>
     */
    protected $attributes = ['is_active' => true];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'period_days' => 'integer',
            'benefit_types' => 'array',
            'is_active' => 'boolean',
        ];
    }

    protected static function newFactory(): DoubleDippingRuleFactory
    {
        return DoubleDippingRuleFactory::new();
    }

    /** Whether this rule applies to the given benefit type. */
    public function appliesTo(string $benefitType): bool
    {
        return empty($this->benefit_types) || in_array($benefitType, $this->benefit_types, true);
    }
}
