<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Models;

use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A scheduled report (PRD FR-RPT-04). Captures WHAT to generate (a standard key or a
 * saved ad-hoc definition), the frequency/format/delivery, the owner's SCOPE, and the
 * validated recipients. Runs unattended on the scheduler; the captured scope + covered
 * recipients guarantee it never delivers out-of-scope data. Personal to owner.
 *
 * @property string $id
 * @property string $name
 * @property string|null $report_key
 * @property string|null $report_definition_id
 * @property string $format
 * @property string $frequency
 * @property string $delivery
 * @property string $status
 * @property string $scope_kind
 * @property string $scope_label
 * @property list<string>|null $scope_mda_ids
 * @property list<string>|null $scope_programme_ids
 * @property list<string> $recipient_user_ids
 * @property string|null $owner_user_id
 * @property string|null $owner_mda_id
 * @property Carbon|null $last_run_on
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ReportDefinition|null $definition
 */
class ReportSchedule extends Model
{
    use HasUuids;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_PAUSED = 'paused';

    public const DELIVERY_LINK = 'link';

    public const DELIVERY_ATTACHMENT = 'attachment';

    /** @var list<string> */
    public const FREQUENCIES = ['daily', 'weekly', 'monthly'];

    protected $table = 'report_schedules';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name', 'report_key', 'report_definition_id', 'format', 'frequency', 'delivery', 'status',
        'scope_kind', 'scope_label', 'scope_mda_ids', 'scope_programme_ids', 'recipient_user_ids',
        'owner_user_id', 'owner_mda_id', 'last_run_on',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scope_mda_ids' => 'array',
            'scope_programme_ids' => 'array',
            'recipient_user_ids' => 'array',
            'last_run_on' => 'date',
        ];
    }

    /**
     * @return BelongsTo<ReportDefinition, $this>
     */
    public function definition(): BelongsTo
    {
        return $this->belongsTo(ReportDefinition::class, 'report_definition_id');
    }

    public function toScope(): DashboardScope
    {
        return DashboardScope::rehydrate($this->scope_kind, $this->scope_mda_ids, $this->scope_programme_ids, $this->scope_label);
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /** Whether the schedule is due to run on a given day (active + frequency elapsed). */
    public function dueOn(Carbon $today): bool
    {
        if (! $this->isActive()) {
            return false;
        }
        if ($this->last_run_on === null) {
            return true;
        }

        return match ($this->frequency) {
            'daily' => $this->last_run_on->lt($today->copy()->startOfDay()),
            'weekly' => $this->last_run_on->lte($today->copy()->subDays(7)->startOfDay()),
            'monthly' => $this->last_run_on->lt($today->copy()->startOfMonth()),
            default => false,
        };
    }
}
