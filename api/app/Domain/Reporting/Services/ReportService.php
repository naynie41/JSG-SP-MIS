<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Models\User;
use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Jobs\GenerateReport;
use App\Domain\Reporting\Models\ReportRun;
use App\Domain\Reporting\Models\ReportSchedule;
use App\Domain\Reporting\Reports\AdHoc\AdHocDefinition;
use App\Domain\Reporting\Reports\AdHoc\AdHocReportBuilder;
use App\Domain\Reporting\Reports\ReportCatalogue;
use App\Domain\Reporting\Support\DashboardScope;
use RuntimeException;

/**
 * Creates a report run and queues its generation (PRD FR-RPT-03/04), for standard,
 * ad-hoc and scheduled reports. The SCOPE is captured on the run, so the queued job
 * renders exactly what the requester (or the schedule owner) was entitled to see —
 * even though it runs later without an authenticated session.
 */
class ReportService
{
    public function __construct(
        private readonly DashboardScopeResolver $resolver,
        private readonly AdHocReportBuilder $adHoc,
    ) {}

    /**
     * @param  array<string, mixed>  $params
     */
    public function request(User $user, string $reportKey, ReportFormat $format, array $params = []): ReportRun
    {
        $scope = $this->resolver->forUser($user);

        if (! ReportCatalogue::availableTo($reportKey, $scope)) {
            throw new RuntimeException('This report is not available for your scope.');
        }

        return $this->createRun($format, [
            'report_key' => $reportKey,
            'report_label' => ReportCatalogue::label($reportKey),
            'params' => $params === [] ? null : $params,
        ], $scope, $user->id, $user->mda_id);
    }

    /** Queue an ad-hoc report. Validates the definition against the caller's scope first. */
    public function requestAdHoc(User $user, AdHocDefinition $definition, ReportFormat $format): ReportRun
    {
        $scope = $this->resolver->forUser($user);
        $this->adHoc->validate($definition, $scope); // throws InvalidReportDefinitionException (→ 422)

        return $this->createRun($format, [
            'report_key' => 'adhoc',
            'report_label' => $definition->label(),
            'definition' => $definition->toArray(),
        ], $scope, $user->id, $user->mda_id);
    }

    /**
     * Generate a run from a schedule (PRD FR-RPT-04). Uses the schedule's CAPTURED
     * scope + validated recipients — it never re-resolves from a (possibly changed)
     * user, so an unattended run honours exactly the scope the schedule was created
     * with, and the run is delivered only to the covered recipients.
     */
    public function runFromSchedule(ReportSchedule $schedule): ReportRun
    {
        return $this->createRun(
            ReportFormat::from($schedule->format),
            [
                ...$this->scheduleReportAttributes($schedule),
                'schedule_id' => $schedule->id,
                'recipient_user_ids' => $schedule->recipient_user_ids,
                'delivery' => $schedule->delivery,
            ],
            $schedule->toScope(),
            $schedule->owner_user_id,
            $schedule->owner_mda_id,
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function scheduleReportAttributes(ReportSchedule $schedule): array
    {
        if ($schedule->report_definition_id !== null && $schedule->definition !== null) {
            return [
                'report_key' => 'adhoc',
                'report_label' => $schedule->name,
                'definition' => $schedule->definition->toAdHoc()->toArray(),
            ];
        }

        return ['report_key' => (string) $schedule->report_key, 'report_label' => $schedule->name];
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function createRun(ReportFormat $format, array $attributes, DashboardScope $scope, ?string $requestedBy, ?string $requestedMdaId): ReportRun
    {
        $run = ReportRun::create([
            ...$attributes,
            'format' => $format->value,
            'status' => ReportRun::STATUS_PENDING,
            'scope_kind' => $scope->kind,
            'scope_label' => $scope->label,
            'scope_mda_ids' => $scope->mdaIds,
            'scope_programme_ids' => $scope->programmeIds,
            'requested_by' => $requestedBy,
            'requested_mda_id' => $requestedMdaId,
        ]);

        GenerateReport::dispatch($run->id);

        return $run;
    }
}
