<?php

declare(strict_types=1);

namespace App\Domain\Reporting\Gis;

use App\Domain\Access\Scopes\MdaScope;
use App\Domain\Benefit\Models\Benefit;
use App\Domain\Benefit\Services\LedgerAggregator;
use App\Domain\Registry\Models\Beneficiary;
use App\Domain\Reporting\Support\DashboardScope;
use Illuminate\Support\Str;

/**
 * Coverage aggregates for the GIS map by admin level (PRD FR-GIS-01), reusing the same
 * scoped ledger aggregation the dashboards use. Rows are keyed by a slug that matches
 * {@see GeoBoundary::$code}, so the controller can join them to boundary shapes for the
 * choropleth (or serve them as the ranked-table fallback when no boundaries are loaded).
 * De-identified aggregate data only.
 */
class GisCoverageService
{
    public function __construct(private readonly LedgerAggregator $ledger) {}

    /**
     * @return list<array{key: string, name: string, beneficiary_count: int, benefit_count: int, benefit_value: int}>
     */
    public function coverage(DashboardScope $scope, string $level): array
    {
        $column = $level === GeoBoundary::LEVEL_WARD ? 'ward' : 'lga';

        $rows = [];
        foreach ($this->beneficiaryCounts($scope, $column) as $raw => $count) {
            $key = $this->slug($raw);
            $rows[$key] = ['key' => $key, 'name' => $this->title($raw), 'beneficiary_count' => $count, 'benefit_count' => 0, 'benefit_value' => 0];
        }

        foreach ($this->ledger->scopedGroup($column, $scope->mdaIds, $scope->programmeIds) as $group) {
            $raw = $group['key'] === null ? '' : (string) $group['key'];
            $key = $this->slug($raw);
            $rows[$key] ??= ['key' => $key, 'name' => $this->title($raw), 'beneficiary_count' => 0, 'benefit_count' => 0, 'benefit_value' => 0];
            $rows[$key]['benefit_count'] = (int) $group['benefit_count'];
            $rows[$key]['benefit_value'] = (int) $group['total_value'];
        }

        return array_values($rows);
    }

    /**
     * Beneficiary counts by the admin column, scoped like the dashboard registry
     * metric (owner MDA; or, for a partner, the beneficiaries served by funded programmes).
     *
     * @return array<string, int>
     */
    private function beneficiaryCounts(DashboardScope $scope, string $column): array
    {
        $query = Beneficiary::query()->withoutGlobalScope(MdaScope::class);

        if ($scope->isPartner()) {
            $served = Benefit::query()->withoutGlobalScope(MdaScope::class)
                ->whereIn('programme_id', $scope->programmeIds ?? [])
                ->distinct()->pluck('beneficiary_id')->all();
            $query->whereIn('id', $served);
        } elseif ($scope->mdaIds !== null) {
            $query->whereIn('owner_mda_id', $scope->mdaIds);
        }

        $out = [];
        foreach ($query->selectRaw("{$column} as k, count(*) as c")->groupBy($column)->get() as $row) {
            $out[(string) ($row->getAttribute('k') ?? '')] = (int) $row->getAttribute('c');
        }

        return $out;
    }

    private function slug(string $value): string
    {
        $slug = (string) Str::of($value)->lower()->replaceMatches('/[^a-z0-9]+/', '_')->trim('_');

        return $slug === '' ? 'unspecified' : $slug;
    }

    private function title(string $value): string
    {
        if ($value === '') {
            return 'Unspecified';
        }

        return (string) Str::of($value)->replace('_', ' ')->title();
    }
}
