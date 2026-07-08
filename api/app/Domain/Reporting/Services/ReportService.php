<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Models\User;
use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Jobs\GenerateReport;
use App\Domain\Reporting\Models\ReportRun;
use App\Domain\Reporting\Reports\AdHoc\AdHocDefinition;
use App\Domain\Reporting\Reports\AdHoc\AdHocReportBuilder;
use App\Domain\Reporting\Reports\ReportCatalogue;
use App\Domain\Reporting\Support\DashboardScope;
use RuntimeException;

/**
 * Creates a report run for a user and queues its generation (PRD FR-RPT-03), for both
 * standard and ad-hoc reports. The caller's scope is resolved and CAPTURED on the run
 * here, so the queued job renders exactly what the requester was entitled to see —
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

        return $this->queue($user, $scope, $format, [
            'report_key' => $reportKey,
            'report_label' => ReportCatalogue::label($reportKey),
            'params' => $params === [] ? null : $params,
        ]);
    }

    /** Queue an ad-hoc report. Validates the definition against the caller's scope first. */
    public function requestAdHoc(User $user, AdHocDefinition $definition, ReportFormat $format): ReportRun
    {
        $scope = $this->resolver->forUser($user);
        $this->adHoc->validate($definition, $scope); // throws InvalidReportDefinitionException (→ 422)

        return $this->queue($user, $scope, $format, [
            'report_key' => 'adhoc',
            'report_label' => $definition->label(),
            'definition' => $definition->toArray(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    private function queue(User $user, DashboardScope $scope, ReportFormat $format, array $attributes): ReportRun
    {
        $run = ReportRun::create([
            ...$attributes,
            'format' => $format->value,
            'status' => ReportRun::STATUS_PENDING,
            'scope_kind' => $scope->kind,
            'scope_label' => $scope->label,
            'scope_mda_ids' => $scope->mdaIds,
            'scope_programme_ids' => $scope->programmeIds,
            'requested_by' => $user->id,
            'requested_mda_id' => $user->mda_id,
        ]);

        GenerateReport::dispatch($run->id);

        return $run;
    }
}
