<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * A precomputed dashboard metric payload for one resolved scope (PRD FR-RPT-01/02).
 * The request path reads this summary row instead of scanning the raw tables. Not
 * MDA-scoped as a model — access is mediated by the resolved scope (the caller only
 * ever reads the snapshot for their own scope) and the payload is aggregate-only.
 *
 * @property string $id
 * @property string $scope_key
 * @property string $scope_kind
 * @property string|null $scope_label
 * @property array<string, mixed> $metrics
 * @property Carbon $computed_at
 */
class DashboardSnapshot extends Model
{
    use HasUuids;

    protected $table = 'dashboard_snapshots';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'scope_key',
        'scope_kind',
        'scope_label',
        'metrics',
        'computed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'metrics' => 'array',
            'computed_at' => 'datetime',
        ];
    }
}
