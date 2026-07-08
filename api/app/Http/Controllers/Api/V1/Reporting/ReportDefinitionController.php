<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Reporting;

use App\Domain\Reporting\Exceptions\InvalidReportDefinitionException;
use App\Domain\Reporting\Export\ReportFormat;
use App\Domain\Reporting\Models\ReportDefinition;
use App\Domain\Reporting\Reports\AdHoc\AdHocDefinition;
use App\Domain\Reporting\Reports\AdHoc\AdHocReportBuilder;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use App\Domain\Reporting\Services\ReportService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reporting\RunReportDefinitionRequest;
use App\Http\Requests\Reporting\SaveReportDefinitionRequest;
use App\Http\Resources\ReportDefinitionResource;
use App\Http\Resources\ReportRunResource;
use App\Support\ApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Saved ad-hoc report definitions (PRD FR-RPT-03): a user saves a composed definition
 * for reuse (and as the basis for scheduling in 6.6), then generates exports from it.
 * Definitions are personal (queried by `owner_user_id`); each is validated against the
 * owner's scope on save and again at generation time.
 */
class ReportDefinitionController extends Controller
{
    public function __construct(
        private readonly ReportService $reports,
        private readonly DashboardScopeResolver $resolver,
        private readonly AdHocReportBuilder $builder,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $definitions = $this->mine($request)->latest('created_at')->get();

        return ApiResponse::success(['definitions' => ReportDefinitionResource::collection($definitions)->resolve()]);
    }

    /** Save a definition — validated against the owner's scope. */
    public function store(SaveReportDefinitionRequest $request): JsonResponse
    {
        $scope = $this->resolver->forUser($request->user());
        $adHoc = AdHocDefinition::fromArray($request->validated());

        try {
            $this->builder->validate($adHoc, $scope);
        } catch (InvalidReportDefinitionException $e) {
            return ApiResponse::error('INVALID_DEFINITION', $e->getMessage(), [], 422);
        }

        $definition = ReportDefinition::create([
            'name' => (string) $request->input('name'),
            'dataset' => $adHoc->dataset,
            'definition' => ['group_by' => $adHoc->groupBy, 'measures' => $adHoc->measures, 'filters' => $adHoc->filters],
            'owner_user_id' => $request->user()->id,
            'owner_mda_id' => $request->user()->mda_id,
        ]);

        return ApiResponse::success((new ReportDefinitionResource($definition))->resolve(), status: 201);
    }

    public function show(Request $request, string $definition): JsonResponse
    {
        $model = $this->mine($request)->findOrFail($definition);

        return ApiResponse::success((new ReportDefinitionResource($model))->resolve());
    }

    public function destroy(Request $request, string $definition): JsonResponse
    {
        $this->mine($request)->findOrFail($definition)->delete();

        return ApiResponse::success(['deleted' => true]);
    }

    /** Generate an export from a saved definition, under the caller's current scope. */
    public function run(RunReportDefinitionRequest $request, string $definition): JsonResponse
    {
        $model = $this->mine($request)->findOrFail($definition);

        try {
            $run = $this->reports->requestAdHoc(
                $request->user(),
                $model->toAdHoc(),
                ReportFormat::from((string) $request->input('format')),
            );
        } catch (InvalidReportDefinitionException $e) {
            return ApiResponse::error('INVALID_DEFINITION', $e->getMessage(), [], 422);
        }

        return ApiResponse::success((new ReportRunResource($run))->resolve(), status: 201);
    }

    /**
     * @return Builder<ReportDefinition>
     */
    private function mine(Request $request): Builder
    {
        return ReportDefinition::query()->where('owner_user_id', $request->user()->id);
    }
}
