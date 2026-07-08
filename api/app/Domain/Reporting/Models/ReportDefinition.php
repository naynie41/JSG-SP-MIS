<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Models;

use App\Domain\Reporting\Reports\AdHoc\AdHocDefinition;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * A saved ad-hoc report definition (PRD FR-RPT-03), reusable by its owner and the
 * basis for scheduling (6.6). Holds only whitelisted keys — no scope, no PII.
 *
 * @property string $id
 * @property string $name
 * @property string $dataset
 * @property array<string, mixed> $definition
 * @property string|null $owner_user_id
 * @property string|null $owner_mda_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ReportDefinition extends Model
{
    use HasUuids;

    protected $table = 'report_definitions';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'dataset',
        'definition',
        'owner_user_id',
        'owner_mda_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'definition' => 'array',
        ];
    }

    /** Rebuild the ad-hoc definition value object (dataset + saved spec). */
    public function toAdHoc(): AdHocDefinition
    {
        return AdHocDefinition::fromArray([
            'dataset' => $this->dataset,
            'name' => $this->name,
            ...$this->definition,
        ]);
    }
}
