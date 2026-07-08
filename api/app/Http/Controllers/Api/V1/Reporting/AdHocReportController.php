<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Reporting;

use App\Domain\Reporting\Exceptions\InvalidReportDefinitionException;
use App\Domain\Reporting\Export\ReportColumn;
use App\Domain\Reporting\Export\ReportData;
use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Reports\AdHoc\AdHocDatasetRegistry;
use App\Domain\Reporting\Reports\AdHoc\AdHocDefinition;
use App\Domain\Reporting\Reports\AdHoc\AdHocReportBuilder;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use App\Domain\Reporting\Services\ReportService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reporting\AdHocReportRequest;
use App\Http\Requests\Reporting\ExportAdHocRequest;
use App\Http\Resources\ReportRunResource;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Ad-hoc report builder (PRD FR-RPT-03): compose a report from a whitelisted dataset,
 * dimensions, measures and filters — preview it, then export via the shared service.
 * Everything is resolved under the caller's scope; the builder rejects any dataset/
 * column/filter outside the whitelist, so scope and PII masking can't be bypassed.
 */
class AdHocReportController extends Controller
{
    private const PREVIEW_LIMIT = 100;

    public function __construct(
        private readonly ReportService $reports,
        private readonly DashboardScopeResolver $resolver,
        private readonly AdHocReportBuilder $builder,
    ) {}

    /** The datasets + selectable dimensions/measures/filters available to the caller's scope. */
    public function datasets(Request $request): JsonResponse
    {
        $scope = $this->resolver->forUser($request->user());

        return ApiResponse::success(['datasets' => AdHocDatasetRegistry::catalogueFor($scope)]);
    }

    /** Build the definition under the caller's scope and return a capped preview. */
    public function preview(AdHocReportRequest $request): JsonResponse
    {
        $scope = $this->resolver->forUser($request->user());

        try {
            $data = $this->builder->build(AdHocDefinition::fromArray($request->validated()), $scope);
        } catch (InvalidReportDefinitionException $e) {
            return ApiResponse::error('INVALID_DEFINITION', $e->getMessage(), [], 422);
        }

        return ApiResponse::success([
            'title' => $data->title,
            'scope' => ['kind' => $scope->kind, 'label' => $scope->label],
            'columns' => array_map(fn (ReportColumn $c): array => ['label' => $c->label, 'numeric' => $c->numeric], $data->columns),
            'rows' => $this->previewRows($data),
            'row_count' => $data->rowCount(),
            'truncated' => $data->rowCount() > self::PREVIEW_LIMIT,
        ]);
    }

    /** Queue an ad-hoc export in the requested format. */
    public function export(ExportAdHocRequest $request): JsonResponse
    {
        try {
            $run = $this->reports->requestAdHoc(
                $request->user(),
                AdHocDefinition::fromArray($request->validated()),
                ReportFormat::from((string) $request->input('format')),
            );
        } catch (InvalidReportDefinitionException $e) {
            return ApiResponse::error('INVALID_DEFINITION', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new ReportRunResource($run))->resolve(), status: 201);
    }

    /**
     * @return list<list<string>>
     */
    private function previewRows(ReportData $data): array
    {
        return array_map(
            fn (array $row): array => array_map(fn (ReportColumn $c): string => $data->cell($row, $c), $data->columns),
            array_slice($data->rows, 0, self::PREVIEW_LIMIT),
        );
    }
}
