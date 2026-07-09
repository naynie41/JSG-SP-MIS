<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Models\User;
use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Reporting\Exceptions\InvalidReportScheduleException;
use App\Domain\Reporting\Models\ReportDefinition;
use App\Domain\Reporting\Models\ReportSchedule;
use App\Domain\Reporting\Reports\AdHoc\AdHocReportBuilder;
use App\Domain\Reporting\Reports\ReportCatalogue;
use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Support\Carbon;

/**
 * Manages scheduled reports (PRD FR-RPT-04): create/update/delete and the scheduled
 * sweep that generates the due ones. On every create/update it captures the owner's
 * scope and VALIDATES that each recipient's scope covers it — so a schedule can never
 * be configured to deliver out-of-scope data. Actions are audited.
 */
class ReportScheduleService
{
    public function __construct(
        private readonly DashboardScopeResolver $resolver,
        private readonly AdHocReportBuilder $adHoc,
        private readonly ReportService $reports,
        private readonly AuditLogger $audit,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(User $owner, array $data): ReportSchedule
    {
        $scope = $this->resolver->forUser($owner);
        [$reportKey, $definition] = $this->resolveReport($owner, $data);
        $this->assertReportAvailable($scope, $reportKey, $definition);

        $recipients = $this->normalizeRecipients($data);
        $this->assertRecipientsCoverScope($scope, $recipients);

        $schedule = ReportSchedule::create([
            'name' => (string) $data['name'],
            'report_key' => $reportKey,
            'report_definition_id' => $definition?->id,
            'format' => (string) $data['format'],
            'frequency' => (string) $data['frequency'],
            'delivery' => (string) ($data['delivery'] ?? ReportSchedule::DELIVERY_LINK),
            'status' => ReportSchedule::STATUS_ACTIVE,
            'scope_kind' => $scope->kind,
            'scope_label' => $scope->label,
            'scope_mda_ids' => $scope->mdaIds,
            'scope_programme_ids' => $scope->programmeIds,
            'recipient_user_ids' => $recipients,
            'owner_user_id' => $owner->id,
            'owner_mda_id' => $owner->mda_id,
        ]);

        $this->audit->record('report_schedule.created', $schedule, after: $this->auditData($schedule), actor: $owner);

        return $schedule;
    }

    /**
     * Update the mutable fields (name/format/frequency/delivery/status/recipients).
     * Recipients are re-validated against the schedule's captured scope.
     *
     * @param  array<string, mixed>  $data
     */
    public function update(ReportSchedule $schedule, array $data, User $actor): ReportSchedule
    {
        $changes = [];
        foreach (['name', 'format', 'frequency', 'delivery', 'status'] as $field) {
            if (array_key_exists($field, $data)) {
                $changes[$field] = (string) $data[$field];
            }
        }

        if (array_key_exists('recipient_user_ids', $data)) {
            $recipients = $this->normalizeRecipients($data);
            $this->assertRecipientsCoverScope($schedule->toScope(), $recipients);
            $changes['recipient_user_ids'] = $recipients;
        }

        $schedule->update($changes);
        $this->audit->record('report_schedule.updated', $schedule, after: $this->auditData($schedule), actor: $actor);

        return $schedule;
    }

    public function delete(ReportSchedule $schedule, User $actor): void
    {
        $this->audit->record('report_schedule.deleted', $schedule, before: $this->auditData($schedule), actor: $actor);
        $schedule->delete();
    }

    /** Generate every active schedule due on `$today`. Returns the number generated. */
    public function runDue(Carbon $today): int
    {
        $count = 0;

        ReportSchedule::query()->where('status', ReportSchedule::STATUS_ACTIVE)->orderBy('id')
            ->chunkById(100, function ($schedules) use ($today, &$count): void {
                foreach ($schedules as $schedule) {
                    /** @var ReportSchedule $schedule */
                    if (! $schedule->dueOn($today)) {
                        continue;
                    }

                    $this->reports->runFromSchedule($schedule);
                    $schedule->update(['last_run_on' => $today->copy()->startOfDay()]);
                    $this->audit->record('report_schedule.ran', $schedule, after: $this->auditData($schedule));
                    $count++;
                }
            });

        return $count;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array{0: string|null, 1: ReportDefinition|null}
     */
    private function resolveReport(User $owner, array $data): array
    {
        $reportKey = isset($data['report_key']) && $data['report_key'] !== '' ? (string) $data['report_key'] : null;
        $definitionId = isset($data['report_definition_id']) && $data['report_definition_id'] !== '' ? (string) $data['report_definition_id'] : null;

        if (($reportKey === null) === ($definitionId === null)) {
            throw new InvalidReportScheduleException('Provide exactly one of a standard report or a saved definition.');
        }

        if ($definitionId !== null) {
            $definition = ReportDefinition::query()->where('owner_user_id', $owner->id)->find($definitionId);
            if ($definition === null) {
                throw new InvalidReportScheduleException('Unknown saved report definition.');
            }

            return [null, $definition];
        }

        return [$reportKey, null];
    }

    private function assertReportAvailable(DashboardScope $scope, ?string $reportKey, ?ReportDefinition $definition): void
    {
        if ($definition !== null) {
            $this->adHoc->validate($definition->toAdHoc(), $scope); // throws (→ 422)

            return;
        }

        if ($reportKey === null || $reportKey === 'adhoc' || ! ReportCatalogue::availableTo($reportKey, $scope)) {
            throw new InvalidReportScheduleException('This report is not available for your scope.');
        }
    }

    /**
     * @param  array<string, mixed>  $data
     * @return list<string>
     */
    private function normalizeRecipients(array $data): array
    {
        return array_values(array_unique(array_map('strval', (array) ($data['recipient_user_ids'] ?? []))));
    }

    /**
     * @param  list<string>  $recipientIds
     */
    private function assertRecipientsCoverScope(DashboardScope $scope, array $recipientIds): void
    {
        if ($recipientIds === []) {
            throw new InvalidReportScheduleException('Add at least one recipient.');
        }

        foreach ($recipientIds as $id) {
            $user = User::query()->withoutGlobalScope(MdaScope::class)->find($id);
            if ($user === null) {
                throw new InvalidReportScheduleException('Unknown recipient.');
            }
            if (! $this->resolver->forUser($user)->covers($scope)) {
                throw new InvalidReportScheduleException('A recipient is not permitted to receive a report for this scope.');
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function auditData(ReportSchedule $schedule): array
    {
        return [
            'report' => $schedule->report_key ?? 'adhoc',
            'format' => $schedule->format,
            'frequency' => $schedule->frequency,
            'delivery' => $schedule->delivery,
            'status' => $schedule->status,
            'recipient_count' => count($schedule->recipient_user_ids),
        ];
    }
}
