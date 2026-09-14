<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Reporting;

use App\Domain\Audit\Services\AuditLogger;
use App\Domain\Reporting\DuplicateReview\DuplicateReviewFilter;
use App\Domain\Reporting\DuplicateReview\DuplicateReviewReport;
use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use App\Domain\Reporting\Services\ReportService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reporting\DuplicateReviewRequest;
use App\Http\Resources\ReportRunResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

/**
 * The duplicate review report (FR-DUP): the match queue's state, on screen and as a file.
 *
 * Scope is resolved from the caller and applied inside the report; a scope without
 * access to review data (a partner, or state-wide oversight without governance) is
 * refused outright rather than shown an empty queue it could mistake for a clear one.
 */
class DuplicateReviewReportController extends Controller
{
    public function __construct(
        private readonly DuplicateReviewReport $report,
        private readonly DashboardScopeResolver $resolver,
        private readonly ReportService $reports,
    ) {}

    public function show(DuplicateReviewRequest $request): JsonResponse
    {
        $scope = $this->resolver->forUser($request->user());

        if (! DuplicateReviewReport::availableTo($scope)) {
            return $this->unavailable();
        }

        return ApiResponse::success(
            $this->report->build($scope, DuplicateReviewFilter::fromArray($request->validated()))
        );
    }

    public function export(DuplicateReviewRequest $request, AuditLogger $audit): JsonResponse
    {
        $user = $request->user();
        $scope = $this->resolver->forUser($user);

        if (! DuplicateReviewReport::availableTo($scope)) {
            return $this->unavailable();
        }

        $filter = DuplicateReviewFilter::fromArray($request->validated());
        $format = ReportFormat::from((string) $request->input('format', ReportFormat::Pdf->value));
        $run = $this->reports->queueDuplicateReviewExport($user, $scope, $filter, $format);

        $audit->record('report.duplicate_review_exported', $run, after: [
            'filters' => $filter->toArray(),
            'format' => $format->value,
            'scope_kind' => $scope->kind,
            'scope_label' => $scope->label,
        ], actor: $user);

        return ApiResponse::success((new ReportRunResource($run))->resolve(), status: 202);
    }

    private function unavailable(): JsonResponse
    {
        return ApiResponse::error('FORBIDDEN', 'The duplicate review report is not available for your scope.', [], 403);
    }
}
