<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Reporting;

use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Reporting\Exceptions\InvalidReportDefinitionException;
use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Segments\SegmentAccess;
use App\Domain\Reporting\Segments\SegmentDefinition;
use App\Domain\Reporting\Segments\SegmentDimension;
use App\Domain\Reporting\Segments\SegmentDimensionRegistry;
use App\Domain\Reporting\Segments\SegmentReportService;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use App\Domain\Reporting\Services\ReportService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reporting\SegmentReportRequest;
use App\Http\Resources\ReportRunResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * The filtered report builder (PRD FR-RPT-03).
 *
 * Compose a population from segmentable dimensions, see it as a table (and optionally
 * as a breakdown chart), then export it through the shared report pipeline.
 *
 * The builder is NOT a way around the export matrix. Every response here is shaped by
 * {@see SegmentAccess}, resolved once from the caller's permissions and scope:
 * a Development Partner or Executive gets counts and never rows; an MDA Admin gets its
 * own MDA's rows; identifiers are masked unless `export.reveal_pii`; and small groups
 * are suppressed on any tier describing people the caller does not own.
 */
class SegmentReportController extends Controller
{
    public function __construct(
        private readonly SegmentDimensionRegistry $dimensions,
        private readonly SegmentReportService $segments,
        private readonly DashboardScopeResolver $resolver,
        private readonly ReportService $reports,
    ) {}

    /**
     * The filter catalogue.
     *
     * Derived from the canonical schema plus the system dimensions, so a new
     * segmentable schema field appears here — and therefore in the UI — with no change
     * to this endpoint.
     */
    public function dimensions(Request $request): JsonResponse
    {
        $access = $this->accessFor($request);

        return ApiResponse::success([
            'dimensions' => array_values(array_map(
                static fn (SegmentDimension $d): array => $d->toArray(),
                $this->dimensions->all(),
            )),
            'tier' => $access->tier,
            'reveal_pii' => $access->revealPii,
            'cell_size_guard' => $access->cellSizeGuard,
            'minimum_cell_size' => (int) config('reporting.min_cell_size', 5),
        ]);
    }

    /** Run the composed query and return a page of the result. */
    public function preview(SegmentReportRequest $request): JsonResponse
    {
        $access = $this->accessFor($request);

        try {
            $definition = SegmentDefinition::fromArray($request->validated(), $this->dimensions);
        } catch (InvalidReportDefinitionException $e) {
            return ApiResponse::error('INVALID_DEFINITION', $e->getMessage(), [], 422);
        }

        return ApiResponse::success(
            $this->segments->preview($definition, $access, (int) $request->input('page', 1))
        );
    }

    /**
     * Queue the export.
     *
     * Audited BEFORE the run is queued and independently of it: the record that a named
     * population was pulled, by whom, under which scope and with what row count is the
     * point of the audit, and it must survive a generation that later fails.
     */
    public function export(SegmentReportRequest $request, AuditLogger $audit): JsonResponse
    {
        $access = $this->accessFor($request);

        try {
            $definition = SegmentDefinition::fromArray($request->validated(), $this->dimensions);
        } catch (InvalidReportDefinitionException $e) {
            return ApiResponse::error('INVALID_DEFINITION', $e->getMessage(), [], 422);
        }

        $format = ReportFormat::from((string) $request->input('format', 'csv'));
        $run = $this->reports->queueSegmentExport($request->user(), $definition, $access, $format);

        $audit->record('report.segment_exported', $run, after: [
            'definition' => $definition->toArray(),
            'format' => $format->value,
            'tier' => $access->tier,
            'scope_kind' => $access->scope->kind,
            'scope_label' => $access->scope->label,
            'reveal_pii' => $access->revealPii,
            'cell_size_guard' => $access->cellSizeGuard,
            'row_count' => $this->segments->total($definition, $access),
        ], actor: $request->user());

        return ApiResponse::success((new ReportRunResource($run))->resolve(), status: 202);
    }

    private function accessFor(Request $request): SegmentAccess
    {
        $user = $request->user();

        return SegmentAccess::forUser($user, $this->resolver->forUser($user));
    }
}
