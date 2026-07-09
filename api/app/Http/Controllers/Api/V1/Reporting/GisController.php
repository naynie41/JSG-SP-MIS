<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Reporting;

use App\Domain\Reporting\Gis\GeoBoundary;
use App\Domain\Reporting\Gis\GisCoverageService;
use App\Domain\Reporting\Services\DashboardScopeResolver;
use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * GIS coverage for the map dashboard (PRD FR-GIS-01). Returns coverage aggregated by
 * LGA/Ward for the caller's scope. When boundaries are loaded it emits a GeoJSON
 * FeatureCollection (choropleth); when none are loaded it DEGRADES GRACEFULLY to the
 * ranked coverage rows (`mode: table`) so the page never breaks. Aggregate data only.
 */
class GisController extends Controller
{
    public function __construct(
        private readonly DashboardScopeResolver $resolver,
        private readonly GisCoverageService $coverage,
    ) {}

    public function coverage(Request $request): JsonResponse
    {
        $level = $request->query('level') === GeoBoundary::LEVEL_WARD ? GeoBoundary::LEVEL_WARD : GeoBoundary::LEVEL_LGA;
        $scope = $this->resolver->forUser($request->user());

        $rows = $this->coverage->coverage($scope, $level);
        usort($rows, fn (array $a, array $b): int => $b['beneficiary_count'] <=> $a['beneficiary_count']);

        $boundaries = GeoBoundary::query()->where('level', $level)->get();

        return ApiResponse::success([
            'level' => $level,
            'scope' => ['kind' => $scope->kind, 'label' => $scope->label],
            'mode' => $boundaries->isEmpty() ? 'table' : 'choropleth',
            'rows' => $rows,
            'feature_collection' => $boundaries->isEmpty() ? null : $this->featureCollection($boundaries, $rows),
        ]);
    }

    /**
     * A GeoJSON FeatureCollection joining each boundary shape to its coverage metrics.
     *
     * @param  Collection<int, GeoBoundary>  $boundaries
     * @param  list<array{key: string, name: string, beneficiary_count: int, benefit_count: int, benefit_value: int}>  $rows
     * @return array<string, mixed>
     */
    private function featureCollection($boundaries, array $rows): array
    {
        $byKey = [];
        foreach ($rows as $row) {
            $byKey[$row['key']] = $row;
        }

        $features = $boundaries->map(function (GeoBoundary $boundary) use ($byKey): array {
            $metric = $byKey[$boundary->code] ?? ['beneficiary_count' => 0, 'benefit_count' => 0, 'benefit_value' => 0];

            return [
                'type' => 'Feature',
                'geometry' => $boundary->geometry,
                'properties' => [
                    'code' => $boundary->code,
                    'name' => $boundary->name,
                    'level' => $boundary->level,
                    'beneficiary_count' => (int) $metric['beneficiary_count'],
                    'benefit_count' => (int) $metric['benefit_count'],
                    'benefit_value' => (int) $metric['benefit_value'],
                ],
            ];
        })->all();

        return ['type' => 'FeatureCollection', 'features' => $features];
    }
}
