<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Reporting;

use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Reporting\Export\ExecutiveExportBuilder;
use App\Domain\Reporting\Export\ReportExporterRegistry;
use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use App\Domain\Reporting\Services\DashboardService;
use App\Domain\Reporting\Support\DashboardFilter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Export the CURRENT (scoped + filtered) executive dashboard as CSV/Excel/PDF (PRD
 * FR-RPT-03). Gated by `reporting.export` — the AGGREGATE reporting export permission,
 * NOT `beneficiary.export`/`export.reveal_pii` — so an executive export is always
 * de-identified aggregates (SECURITY.md). The scope + filter are resolved server-side,
 * so the file can only ever contain what the caller may already see.
 */
class DashboardExportController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboard,
        private readonly DashboardScopeResolver $resolver,
        private readonly ExecutiveExportBuilder $builder,
        private readonly ReportExporterRegistry $exporters,
        private readonly AuditLogger $audit,
    ) {}

    public function export(Request $request): StreamedResponse
    {
        $format = ReportFormat::tryFrom((string) $request->query('format', 'csv')) ?? ReportFormat::Csv;
        $scope = $this->resolver->forUser($request->user());
        $filter = DashboardFilter::fromRequest($request);

        $metrics = $this->dashboard->forUser($request->user(), $filter)['metrics'];
        $data = $this->builder->build($metrics, $scope->label);
        $bytes = $this->exporters->for($format)->render($data);

        $this->audit->record('dashboard.exported', after: [
            'scope' => $scope->key(),
            'tier' => $scope->tier(),
            'format' => $format->value,
            'filters' => $filter->toArray(),
            'rows' => $data->rowCount(),
        ]);

        $filename = 'executive-dashboard-'.now()->format('Ymd-His').'.'.$format->extension();

        return response()->streamDownload(static function () use ($bytes): void {
            echo $bytes;
        }, $filename, ['Content-Type' => $format->mimeType()]);
    }
}
