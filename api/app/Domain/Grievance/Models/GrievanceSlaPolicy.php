<?php

declare(strict_types=1);

namespace App\Domain\Grievance\Models;

use App\Domain\Audit\Concerns\Auditable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * An admin-editable SLA window for a grievance category (PRD FR-GRM-03): how many
 * hours a grievance of `category` may remain unresolved before it is overdue. Global
 * config (not MDA-scoped); changes are audited.
 *
 * @property string $id
 * @property string $category
 * @property int $hours
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class GrievanceSlaPolicy extends Model
{
    use Auditable, HasUuids;

    protected $table = 'grievance_sla_policies';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'category',
        'hours',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hours' => 'integer',
        ];
    }
}
