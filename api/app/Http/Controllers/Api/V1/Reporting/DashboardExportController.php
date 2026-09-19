<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Reporting;

use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Reporting\Export\DashboardBoardExportBuilder;
use App\Domain\Reporting\Export\ExecutiveExportBuilder;
use App\Domain\Reporting\Export\ReportExporterRegistry;
use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Gis\GeoBoundary;
use App\Domain\Reporting\Gis\GisCoverageService;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use App\Domain\Reporting\Services\DashboardService;
use App\Domain\Reporting\Support\DashboardFilter;
use App\Domain\Reporting\Support\DashboardScope;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
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
        private readonly DashboardBoardExportBuilder $boardBuilder,
        private readonly GisCoverageService $coverage,
        private readonly ReportExporterRegistry $exporters,
        private readonly AuditLogger $audit,
    ) {}

    public function export(Request $request): StreamedResponse|JsonResponse
    {
        $scope = $this->resolver->forUser($request->user());
        $filter = DashboardFilter::fromRequest($request);

        // A BOARD export is the reporting dashboard as a page — tiles, charts, map.
        // An MDA has no other kind. A state-wide caller does: the executive suite
        // exports the same endpoint as CSV/Excel/PDF sections, so the console asks for
        // the board explicitly rather than having it inferred from the scope.
        if ($scope->kind === DashboardScope::KIND_MDA || $request->query('view') === 'board') {
            return $this->boardExport($request, $scope, $filter);
        }

        $format = ReportFormat::tryFrom((string) $request->query('format', 'csv')) ?? ReportFormat::Csv;

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

    /**
     * A reporting dashboard as the page it was exported from: PDF only, laid out as the
     * board is and reading the same figures (see DashboardBoardExportBuilder).
     *
     * PDF only, deliberately. This export is a picture of a page — its charts and its
     * map are the point — and CSV cannot carry any of that. A caller who wants the
     * figures as rows has the report builder, which is what it is for.
     */
    private function boardExport(Request $request, DashboardScope $scope, DashboardFilter $filter): StreamedResponse|JsonResponse
    {
        if ((string) $request->query('format', ReportFormat::Pdf->value) !== ReportFormat::Pdf->value) {
            return ApiResponse::error('PDF_ONLY', 'The dashboard exports as a PDF.', [], 422);
        }

        $stateWide = $scope->kind !== DashboardScope::KIND_MDA;

        // The letterhead names the MDA ("Ministry of Health"), not the scope's generic
        // label. A state-wide board has no single agency to name, so it keeps the scope's.
        $mdaName = $stateWide ? null : $request->user()->mda?->name;

        // The map: the same scoped LGA coverage and boundary shapes the dashboard's map uses.
        $boundaries = GeoBoundary::query()->where('level', GeoBoundary::LEVEL_LGA)->get(['code', 'name', 'geometry']);
        $map = $boundaries->isEmpty() ? null : [
            'rows' => $this->coverage->coverage($scope, GeoBoundary::LEVEL_LGA, $filter),
            'boundaries' => $boundaries->map(static fn (GeoBoundary $b): array => ['code' => $b->code, 'name' => $b->name, 'geometry' => $b->geometry])->all(),
        ];

        $data = $this->boardBuilder->build(
            $this->dashboard->forUser($request->user(), $filter),
            $filter,
            is_string($mdaName) ? $mdaName : null,
            $map,
            $stateWide,
        );
        $bytes = $this->exporters->for(ReportFormat::Pdf)->render($data);

        $this->audit->record('dashboard.exported', after: [
            'report' => $stateWide ? 'state_dashboard' : 'mda_dashboard',
            'scope' => $scope->key(),
            'tier' => $scope->tier(),
            'format' => ReportFormat::Pdf->value,
            'filters' => $filter->toArray(),
            'rows' => $data->rowCount(),
        ]);

        $name = ($stateWide ? 'state-dashboard-' : 'mda-dashboard-').now()->format('Ymd-His').'.pdf';

        return response()->streamDownload(static function () use ($bytes): void {
            echo $bytes;
        }, $name, ['Content-Type' => ReportFormat::Pdf->mimeType()]);
    }
}
