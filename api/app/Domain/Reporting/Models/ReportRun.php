<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Models;

use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * A generated report run (PRD FR-RPT-03). Holds the requested report + format, the
 * captured scope, and the lifecycle/status of generation. Personal to the requester
 * (queried by `requested_by`); the payload is aggregate-only, so it is not Auditable
 * as a model — generation + downloads are audited explicitly.
 *
 * @property string $id
 * @property string $report_key
 * @property string $report_label
 * @property string $format
 * @property string $status
 * @property string $scope_kind
 * @property string $scope_label
 * @property list<string>|null $scope_mda_ids
 * @property list<string>|null $scope_programme_ids
 * @property array<string, mixed>|null $params
 * @property int|null $row_count
 * @property string|null $file_path
 * @property string|null $file_name
 * @property string|null $error
 * @property string|null $requested_by
 * @property string|null $requested_mda_id
 * @property Carbon|null $completed_at
 * @property Carbon|null $created_at
 */
class ReportRun extends Model
{
    use HasUuids;

    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_READY = 'ready';

    public const STATUS_FAILED = 'failed';

    protected $table = 'report_runs';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'report_key', 'report_label', 'format', 'status',
        'scope_kind', 'scope_label', 'scope_mda_ids', 'scope_programme_ids', 'params',
        'row_count', 'file_path', 'file_name', 'error',
        'requested_by', 'requested_mda_id', 'completed_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scope_mda_ids' => 'array',
            'scope_programme_ids' => 'array',
            'params' => 'array',
            'row_count' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    /** Rebuild the scope this run was requested under. */
    public function toScope(): DashboardScope
    {
        return DashboardScope::rehydrate($this->scope_kind, $this->scope_mda_ids, $this->scope_programme_ids, $this->scope_label);
    }

    public function isReady(): bool
    {
        return $this->status === self::STATUS_READY;
    }
}
