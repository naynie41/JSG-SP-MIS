<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Services;

use App\Domain\Access\Models\User;
use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Jobs\GenerateReport;
use App\Domain\Reporting\Models\ReportRun;
use App\Domain\Reporting\Reports\ReportCatalogue;
use RuntimeException;

/**
 * Creates a report run for a user and queues its generation (PRD FR-RPT-03). The
 * caller's scope is resolved and CAPTURED on the run here, so the queued job renders
 * exactly what the requester was entitled to see — even though it runs later without
 * an authenticated session.
 */
class ReportService
{
    public function __construct(private readonly DashboardScopeResolver $resolver) {}

    /**
     * @param  array<string, mixed>  $params
     */
    public function request(User $user, string $reportKey, ReportFormat $format, array $params = []): ReportRun
    {
        $scope = $this->resolver->forUser($user);

        if (! ReportCatalogue::availableTo($reportKey, $scope)) {
            throw new RuntimeException('This report is not available for your scope.');
        }

        $run = ReportRun::create([
            'report_key' => $reportKey,
            'report_label' => ReportCatalogue::label($reportKey),
            'format' => $format->value,
            'status' => ReportRun::STATUS_PENDING,
            'scope_kind' => $scope->kind,
            'scope_label' => $scope->label,
            'scope_mda_ids' => $scope->mdaIds,
            'scope_programme_ids' => $scope->programmeIds,
            'params' => $params === [] ? null : $params,
            'requested_by' => $user->id,
            'requested_mda_id' => $user->mda_id,
        ]);

        GenerateReport::dispatch($run->id);

        return $run;
    }
}
