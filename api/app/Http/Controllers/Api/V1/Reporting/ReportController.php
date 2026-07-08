<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Reporting;

use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Models\ReportRun;
use App\Domain\Reporting\Reports\ReportCatalogue;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use App\Domain\Reporting\Services\ReportService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reporting\GenerateReportRequest;
use App\Http\Resources\ReportRunResource;
use App\Support\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Standard report generation + download (PRD FR-RPT-03). The catalogue and every run
 * are scoped to the caller (the report is built under the caller's resolved scope);
 * runs are personal (queried by `requested_by`). Generation is queued; downloads are
 * audited. Exports carry only aggregate, de-identified data.
 */
class ReportController extends Controller
{
    public function __construct(
        private readonly ReportService $reports,
        private readonly DashboardScopeResolver $resolver,
        private readonly AuditLogger $audit,
    ) {}

    /** The standard reports available to the caller's scope + the export formats. */
    public function catalogue(Request $request): JsonResponse
    {
        $scope = $this->resolver->forUser($request->user());

        return ApiResponse::success([
            'reports' => ReportCatalogue::availableFor($scope),
            'formats' => array_map(fn (ReportFormat $f): array => ['value' => $f->value, 'label' => $f->label()], ReportFormat::cases()),
        ]);
    }

    /** My report runs (most recent first). */
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max($request->integer('per_page', 20), 1), 100);
        $page = $this->mine($request)->latest('created_at')->paginate($perPage);

        return ApiResponse::paginated(ReportRunResource::collection($page->items())->resolve(), $page);
    }

    /** Queue a report for generation under the caller's scope. */
    public function store(GenerateReportRequest $request): JsonResponse
    {
        try {
            $run = $this->reports->request(
                $request->user(),
                (string) $request->input('report_key'),
                ReportFormat::from((string) $request->input('format')),
                (array) $request->input('params', []),
            );
        } catch (RuntimeException $e) {
            return ApiResponse::error('REPORT_UNAVAILABLE', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new ReportRunResource($run))->resolve(), status: 201);
    }

    public function show(Request $request, string $report): JsonResponse
    {
        $run = $this->mine($request)->findOrFail($report);

        return ApiResponse::success((new ReportRunResource($run))->resolve());
    }

    /** Download a ready report file — re-checks ownership, then audits the access. */
    public function download(Request $request, string $report): StreamedResponse|JsonResponse
    {
        $run = $this->mine($request)->findOrFail($report);

        if (! $run->isReady() || $run->file_path === null || ! Storage::disk('local')->exists($run->file_path)) {
            return ApiResponse::error('REPORT_NOT_READY', 'This report is not ready to download.', [], 409);
        }

        $this->audit->record('report.downloaded', $run, after: [
            'report_key' => $run->report_key,
            'format' => $run->format,
        ]);

        return Storage::disk('local')->download(
            $run->file_path,
            $run->file_name ?? "report.{$run->format}",
            ['Content-Type' => ReportFormat::from($run->format)->mimeType()],
        );
    }

    /**
     * @return Builder<ReportRun>
     */
    private function mine(Request $request): Builder
    {
        return ReportRun::query()->where('requested_by', $request->user()->id);
    }
}
